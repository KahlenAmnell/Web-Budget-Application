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
