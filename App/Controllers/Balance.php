<?php

namespace App\Controllers;

use App\Models\Expense;
use \Core\View;
use \App\Models\Income;

/**
 * Balance controller
 */
class Balance extends \Core\Controller
{
    /**
     * Show balance page
     * 
     * @return void
     */
    public function indexAction()
    {
        $earlierDate = date("Y-m") . "-01";
        $laterDate = date("Y-m") . "-31";
        $userIncomes = Income::getUserIncomes($earlierDate, $laterDate);
        $userExpenses = Expense::getUserExpenses($earlierDate, $laterDate);
        View::renderTemplate('Balance/index.html', [
            'incomes' => $userIncomes,
            'sumOfIncomes' => array_sum($userIncomes),
            'expenses' => $userExpenses,
            'sumOfExpenses' => array_sum($userExpenses),
            'choosenPerion' => 'currentMonth'
        ]);
    }

    public function periodAction()
    {
        $period = $_POST['period'];
        if (isset($_POST['period'])) {
            if ($period == "currentMonth") {
                $earlierDate = date("Y-m") . "-01";
                $laterDate = date("Y-m") . "-31";
            }
            else if ($period == "previousMonth") {
                if (date("m") == "01") {
                    $month = "12";
                    $year = date("Y") - 1;
                } else {
                    $month = date("m") - 1;
                    $year = date("Y");
                }
                $earlierDate = $year . "-" . $month . "-01";
                $laterDate = $year . "-" . $month . "-31";
            }
            else if ($period == "currentYear") {
                $earlierDate = date("Y") . "-01-01";
                $laterDate = date("Y") . "-12-31";
            }
            else if ($period == "other") {
                $earlierDate = $_POST['beginingDate'];
                $laterDate = $_POST['endingDate'];
            }
            unset($_POST['period']);
        } else {
            $earlierDate = date("Y-m") . "-01";
            $laterDate = date("Y-m") . "-31";
        }
        $userIncomes = Income::getUserIncomes($earlierDate, $laterDate);
        $userExpenses = Expense::getUserExpenses($earlierDate, $laterDate);
        View::renderTemplate('Balance/index.html', [
            'incomes' => $userIncomes,
            'sumOfIncomes' => array_sum($userIncomes),
            'expenses' => $userExpenses,
            'sumOfExpenses' => array_sum($userExpenses),
            'choosenPerion' => $period
        ]);
    }
}
