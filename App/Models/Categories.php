<?php

namespace App\Models;

use PDO;

/**
 * Categories model
 */
class Categories extends \Core\Model
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
     * Save the validate data from the Add category form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
        $this->validate();
        if (empty($this->errors)) {
            $table = $this->setCategoryTable();
            $sql = 'INSERT INTO ';
            $sql .= $table;
            if ($table == 'expense_category_assigned_to_user_id') {
                $sql .= ' (userID, name, categoryLimit) VALUES (:userId, :name, :limit)';
                if (!isset($this->limitAmount)) {
                    $limit = 0;
                } else {
                    $limit = $this->limitAmount;
                }
            } else {
                $sql .= ' (userID, name) VALUES (:userId, :name)';
            }

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $this->categoryName = $this->capitalizeName();
            $stmt->bindValue(':name', $this->categoryName, PDO::PARAM_STR);

            $userId = $_SESSION['user_id'];
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

            if (isset($limit)) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            }

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
        if (isset($this->categoryName)) {
            if ($this->categoryName == '') {
                $this->errors[] = 'Nazwa kategorii jest wymagana';
            }
        }

        //limit
        if (isset($this->limitAmount)) {
            if ($this->limitAmount <= 0) {
                $this->errors[] = 'Limit musi być większy niż 0';
            }
            if ($this->limitAmount != round($this->limitAmount, 2)) {
                $this->errors[] = 'Kwota musi być zaokrąglona do dwóch miejsc po przecinku.';
            }
        }
    }

    /**
     * Convert the string capitalize them e.g. 'heLLo WorLd' to 'Hello world'
     * 
     * @return string Capitalize name of the new category
     */
    protected function capitalizeName()
    {
        return ucfirst(strtolower($this->categoryName));
    }

    /**
     * Set category table
     * 
     * @return string Name of category table
     */
    protected function setCategoryTable()
    {
        var_dump($this->categoryGroup);
        if ($this->categoryGroup == 'incomes') {
            return 'incomes_category_assigned_to_users';
        } elseif ($this->categoryGroup == 'expenses') {
            return 'expense_category_assigned_to_user_id';
        } elseif ($this->categoryGroup == 'payments') {
            return 'payment_methods_assigned_to_users';
        }
    }
}
