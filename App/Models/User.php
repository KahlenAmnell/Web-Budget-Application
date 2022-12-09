<?php

namespace App\Models;

use PDO;
use PDOException;

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

            $sql = 'INSERT INTO users (username, password_hash, email)
                    VALUES (:name, :password_hash, :email)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            return $stmt->execute();
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
     * Test method
     * 
     * @return array
     */
    public static function test()
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT * FROM users');

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
