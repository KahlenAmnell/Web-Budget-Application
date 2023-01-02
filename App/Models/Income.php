<?php

namespace App\Models;

use PDO;

/**
 * Incomes model
 */
class Income extends Finances
{
    /**
     * Get income categories assign to users
     * 
     * @return array Array with income categories of logged in user
     */
    public static function getIncomeCategories()
    {
        return Finances::getCategories('incomes_category_assigned_to_users');
    }

    /**
     * Save the validate data from the AddIncome form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO incomes (userID, incomeCategoryAssignedToUserId, amount, dateOfIncome, incomeComment)
                    VALUES (:id, :category, :amount, :date, :comment)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':category', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

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
        parent::validate();

        //category
        if ($this->category == '') {
            $this->errors[] = 'Musisz podać kategorię przychodu';
        }
    }

    /**
     * 
     */
    public static function getUserIncomes($earlierDate, $laterDate)
    {
        $sql = "SELECT icatu.name, SUM(i.amount) AS amount 
                FROM incomes_Category_Assigned_To_Users AS icatu INNER JOIN incomes AS i
                WHERE i.userID = :id AND icatu.id = i.incomeCategoryAssignedToUserId 
                    AND i.dateOfIncome >= :earlierDate AND i.dateOfIncome <= :laterDate 
                GROUP BY icatu.name 
                ORDER BY amount DESC;";

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
}
