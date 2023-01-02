<?php

namespace App\Models;

use PDO;

/**
 * Exoense model
 */
class Expense extends Finances
{
    /**
     * Get expense categories assign to users
     * 
     * @return array Array with expense categories of logged in user
     */
    public static function getExpenseCategories()
    {
        return Finances::getCategories('expense_category_assigned_to_user_id');
    }

    /**
     * Get payment categories assign to users
     * 
     * @return array Array with payment methods categories of logged in user
     */
    public static function getPaymentCategories()
    {
        return Finances::getCategories('payment_methods_assigned_to_users');
    }

    /**
     * Save the validate data from the AddExpense form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses (userID, expenseCategoryAssignedToUserID, paymentMethodAssignedToUserID, amount, dateOfExpense, expenseComment)
                    VALUES (:id, :category, :paymentMethod, :amount, :date, :comment)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':category', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod', $this->paymentMethod, PDO::PARAM_INT);
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

        //expense category
        if ($this->category == '') {
            $this->errors[] = 'Musisz podać kategorię wydatku.';
        }

        //payment methods category
        if ($this->paymentMethod == '') {
            $this->errors[] = 'Musisz podać kategorię metody płatności.';
        }
    }

    /**
     * 
     */
    public static function getUserExpenses($earlierDate, $laterDate)
    {
        $sql = "SELECT ecatu.name, SUM(e.amount) AS amount FROM expense_Category_Assigned_To_User_ID AS ecatu INNER JOIN expenses AS e
        WHERE e.userID = :id AND ecatu.id = e.expenseCategoryAssignedToUserID AND e.dateOfExpense >= :earlierDate AND e.dateOfExpense <= :laterDate GROUP BY ecatu.name ORDER BY amount DESC;";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':earlierDate', $earlierDate, PDO::PARAM_STR);
        $stmt->bindValue(':laterDate', $laterDate, PDO::PARAM_STR);

        $stmt->execute();

        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[$category["name"]] = $category["amount"];
        }

        return $categories;
    }
}
