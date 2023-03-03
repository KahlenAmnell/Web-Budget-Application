<?php

namespace App\Models;

use PDO;
use App\Dates;

/**
 * Expense model
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
        return Finances::getCategories('expense_Category_Assigned_To_User_ID');
    }

    /**
     * Get payment categories assign to users
     * 
     * @return array Array with payment methods categories of logged in user
     */
    public static function getPaymentCategories()
    {
        return Finances::getCategories('payment_Methods_Assigned_To_Users');
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

        return Finances::getUserFinances($sql, $earlierDate, $laterDate);
    }

    public static function getExpensesList($earlierDate, $laterDate)
    {
        $sql = "SELECT e.id, e.dateOfExpense, ecatu.name, e.amount, e.expenseComment 
        FROM expense_Category_Assigned_To_User_ID AS ecatu INNER JOIN expenses AS e
        WHERE e.userID = :id AND ecatu.id = e.expenseCategoryAssignedToUserID 
            AND e.dateOfExpense >= :earlierDate AND e.dateOfExpense <= :laterDate 
        ORDER BY e.dateOfExpense DESC;";
        return Finances::getListOfFinances($sql, $earlierDate, $laterDate);
    }

    /**
     * Get expense categorie limit assign to user
     * 
     * @return string String with category limit
     */
    public static function getExpenseLimit($id)
    {
        $sql = "SELECT categoryLimit FROM expense_category_assigned_to_user_id WHERE id = :id;";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Get sum of chosen category expenses from month of chosen date
     * 
     * @param string $id Id of chosen category
     * @param string $date Chosen date
     * 
     * @return string Sum of one category expenses from one month
     */
    public static function getCategoryExpenses($id, $date)
    {
        $dates = Dates::setLimitDates($date);
        $earlierDate = $dates['earlierDate'];
        $laterDate = $dates['laterDate'];
        
        $sql = "SELECT SUM(amount) FROM expenses 
        WHERE expenseCategoryAssignedToUserID = :id 
        AND dateOfExpense >= :earlierDate AND dateOfExpense <= :laterDate;";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':earlierDate', $earlierDate, PDO::PARAM_STR);
        $stmt->bindValue(':laterDate', $laterDate, PDO::PARAM_STR);
        
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public static function deleteRecordsOfOneCategoryOfExpenses($id)
    {
        $sql = "DELETE FROM expenses WHERE expenseCategoryAssignedToUserID = :id";

        return Finances::deleteAllRecordsOfOneCategory($sql, $id);
    }
}
