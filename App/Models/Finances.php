<?php

namespace App\Models;

use PDO;

/**
 * Finance base controller
 */
abstract class Finances extends \Core\Model
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
     */
    public static function getCategories($table)
    {
        $userId = $_SESSION['user_id'];
        $sql = 'SELECT * FROM ' . $table . ' WHERE userID = :userId ORDER BY id';

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
     * Validate current data, adding validation errors messages to the errors array property
     * 
     * @return void
     */
    public function validate()
    {
        //amount
        if ($this->amount == '') {
            $this->errors[] = 'Podaj kwotę.';
        } else {
            if ($this->amount <= 0) {
                $this->errors[] = 'Kwota musi być większa niż 0.';
            }
            if ($this->amount != round($this->amount, 2)) {
                $this->errors[] = 'Kwota musi być zaokrąglona do dwóch miejsc po przecinku.';
            }
        }

        //data
        if ($this->date > date("Y-m-d")) {
            $this->errors[] = 'Data nie może być przyszła.';
        }
        if ($this->date < "2000-01-01") {
            $this->errors[] = 'Data nie może wcześniejsza niż 01-01-2000.';
        }
    }

    public static function getUserFinances($sql, $earlierDate, $laterDate)
    {
        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':earlierDate', $earlierDate, PDO::PARAM_STR);
        $stmt->bindValue(':laterDate', $laterDate, PDO::PARAM_STR);

        $stmt->execute();
        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[$category["name"]] = $category["amount"];
        }
        if (isset($categories)) {
            return $categories;
        } else {
            $nothing[] = 0;
            return $nothing;
        }
    }

    public static function getListOfFinances($sql, $earlierDate, $laterDate)
    {
        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':earlierDate', $earlierDate, PDO::PARAM_STR);
        $stmt->bindValue(':laterDate', $laterDate, PDO::PARAM_STR);

        $stmt->execute();

        $list = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($list, $row);
        }
        if (!empty($list)) {
            return $list;
        } else {
            $nothing[] = 0;
            return $nothing;
        }
    }

    /**
     * Delete one, chosen record
     * 
     * @param int $id Record id
     * @param string $table Name of table from which user want to delete record
     * 
     * @return void
     */

    public static function deleteRecord($id, $table)
    {
        $sql = "DELETE FROM $table WHERE id = :id";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Delete records of chosen category
     * 
     * @return boolean True if delete was success and false otherwise
     */
    public static function deleteAllRecordsOfOneCategory($sql, $id)
    {
        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
