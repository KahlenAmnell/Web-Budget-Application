<?php

namespace App\Models;

use PDO;
use \App\Token;
use \Core\View;
use \App\Mail;

/**
 * User model
 */
class User extends \Core\Model
{
    /**
     * Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the validate data from the Signup form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (username, password_hash, email, activation_hash)
                    VALUES (:name, :password_hash, :email, :activation_hash)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            $result = $stmt->execute();

            $id = (int) $db->lastInsertId();

            $this->assignCategories('incomes_Category_Default', 'incomes_Category_Assigned_To_Users', $id);
            $this->assignCategories('expenses_Category_Default', 'expense_Category_Assigned_To_User_ID', $id);
            $this->assignCategories('payment_Methods_Default', 'payment_Methods_Assigned_To_Users', $id);

            return $result;
        }
        return false;
    }

    /**
     * Validate current data, adding validation errors messages to the errors array property
     * 
     * @return void
     */
    public function validate()
    {
        //Name
        if ($this->name == '') {
            $this->errors[] = 'Imię jest wymagane.';
        }

        //email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Adres email jest nieprawidłowy.';
        }

        if (static::emailExist($this->email)) {
            $this->errors[] = 'Istnieje już konto o tym adresie email.';
        }

        //password
        if (strlen($this->password) < 8) {
            $this->errors[] = 'Hasło musi zawierać conajmniej 8 znaków.';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać conajmniej 1 literę.';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać conajmniej jedną cyfrę.';
        }

        if ($this->password != $this->passwordConfirmation) {
            $this->errors[] = 'Podane hasła się nie zgadzają.';
        }
    }

    /**
     * See if a user record already exist with the specified email
     * 
     * @param string $email email address to search for
     * 
     * @return boolean True i frecord already exist with specified email, false otherwise
     */
    public static function emailExist($email)
    {
        $user = static::findbyEmail($email);

        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Find a user model by email address
     * 
     * @param string $email email address to search for
     * 
     * @return mixed User object if found, false otherwise
     */
    public static function findbyEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Assign default categories to new user account
     * 
     * @param string $defaultTable  Table with default categories
     * @param string $finalTable    Table with categories assign to user id
     * @param int    $userId        Id to assign categories
     * 
     * @return void
     */
    public function assignCategories($defaultTable, $finalTable, $userId)
    {
        $sql = "INSERT INTO " . $finalTable . " (userID, name)
                SELECT :userId , name FROM " . $defaultTable;

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Authenticate a user by email and password
     * 
     * @param string $email email address
     * @param string $password password
     * 
     * @return mixed The user object or false if authentication fails 
     */
    public static function authenticate($email, $password)
    {
        $user = static::findbyEmail($email);

        if ($user) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Find a user model by ID
     * 
     * @param string $id The user ID
     * 
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table for this user record
     * 
     * @return boolean True if the login was remembered succesfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 10; // 10 days from now

        $sql = 'INSERT INTO remembered_login (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send an email to the user containing the activation link
     * 
     * @return void
     */
    public function sendActivationEmail()
    {
        $subject = 'Aktywacja konta';

        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/sign-up/activate/' . $this->activation_token;
        $text = 'W celu aktywacji konta wejdź na stronę podaną w poniższym linku. \n' . $url;

        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);

        Mail::send($this->email, $subject, $text, $html);
    }

    /**
     * Activate the user account with the specified activation token
     * 
     * @param string $value Activation token from the URL
     * 
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }
}
