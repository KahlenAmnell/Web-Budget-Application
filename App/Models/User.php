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
     * Save the data from the Signup form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
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
