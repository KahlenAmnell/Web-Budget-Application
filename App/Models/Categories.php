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
    public function capitalizeName($group = null)
    {
        if (!isset($this->categoryGroup)) {
            $this->categoryGroup = $group;
        }
        return ucfirst(strtolower($this->categoryName));
    }

    /**
     * Set category table
     * 
     * @return string Name of category table
     */
    public function setCategoryTable($group = null)
    {
        if (!isset($this->categoryGroup)) {
            $this->categoryGroup = $group;
        }
        if ($this->categoryGroup == 'incomes') {
            return 'incomes_category_assigned_to_users';
        } elseif ($this->categoryGroup == 'expenses') {
            return 'expense_category_assigned_to_user_id';
        } elseif ($this->categoryGroup == 'payments') {
            return 'payment_methods_assigned_to_users';
        }
    }

    /**
     * Update categories
     * 
     * @return void
     */
    public function update()
    {
        $this->validate();
        if (empty($this->errors)) {
            $table = $this->setCategoryTable();
            $sql = 'UPDATE ';
            $sql .= $table;
            if ($table == 'expense_category_assigned_to_user_id') {
                $sql .= ' SET name = :name, categoryLimit = :limit WHERE id = :id';
                if (!isset($this->limitAmount)) {
                    $limit = 0;
                } else {
                    $limit = $this->limitAmount;
                }
            } else {
                $sql .= ' SET name = :name WHERE id = :id';
            }

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $this->categoryName = $this->capitalizeName();
            $stmt->bindValue(':name', $this->categoryName, PDO::PARAM_STR);

            if (isset($limit)) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            }

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
        return false;
    }

    /**
     * See if a user category name already exist
     * 
     * @param string $name New category name
     * @param string $categoryGroup Category group (e.g. expenses)
     * 
     * @return boolean True if name is already exist for user or false otherwise
     */
    public  function checkName()
    {
        $table = $this->setCategoryTable();
        $name = $this->capitalizeName();
        $userId = $_SESSION['user_id'];

        $sql = "SELECT * FROM ";
        $sql .= $table;
        $sql .= ' WHERE userID = :userId AND name = :name ;';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        $stmt->execute();

        $respond = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$respond) {
            return true;
        }

        if(isset($this->ignoreId)){
            if ($respond[0]['id'] == $this->ignoreId) {
                return true;
            }
        }
        return false;
    }
}
