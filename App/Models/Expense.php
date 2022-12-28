<?php

namespace App\Models;

use PDO;

/**
 * Exoense model
 */
class Expense extends \Core\Model
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
     * Get categories assign to users
     * 
     * @return array Array with expense categories of logged in user
     */
    public static function getExpenseCategories()
    {
        $userId = $_SESSION['user_id'];
        $sql = 'SELECT name, id FROM expense_category_assigned_to_user_id WHERE userID = :userId ORDER BY id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $category;
        }
        return $categories;
    }

    /**
     * Get categories assign to users
     * 
     * @return array Array with payment methods categories of logged in user
     */
    public static function getPaymentCategories()
    {
        $userId = $_SESSION['user_id'];
        $sql = 'SELECT name, id FROM payment_methods_assigned_to_users WHERE userID = :userId ORDER BY id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $category;
        }
        return $categories;
    }
}
