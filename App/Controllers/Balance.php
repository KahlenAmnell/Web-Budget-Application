<?php

namespace App\Controllers;

use App\Models\Expense;
use \Core\View;
use \App\Models\Income;

/**
 * Balance controller
 */
class Balance extends Authenticated
{
    /**
     * Show balance page
     * 
     * @return void
     */
    public function indexAction()
    {
        $data = $this->balanceData();
        View::renderTemplate('Balance/index.html', $data);
    }

    /**
     * Set sent rendering page data
     * 
     * @return array Contains data
     */
    public function balanceData()
    {
        if (isset($_POST['period'])) {
            $period = $_POST['period'];
        } else {
            $period = 'currentMonth';
        }
        unset($_POST['period']);

        $dates = $this->finansesDates($period);

        $userIncomes = Income::getUserIncomes($dates['earlierDate'], $dates['laterDate']);
        $userExpenses = Expense::getUserExpenses($dates['earlierDate'], $dates['laterDate']);

        $sumOfIncomes = array_sum($userIncomes);
        $sumOfExpenses = array_sum($userExpenses);
        $incomeDataPoints = array();
        $expensesDataPoints = array();

        if (!empty($userIncomes) && $sumOfIncomes > 0) {
            foreach ($userIncomes as $incomeName => $amount) {
                $percent = $amount / $sumOfIncomes * 100;
                $incomeDataPoints[] = array("label" => $incomeName, "y" => $percent);
            }
        }

        if (!empty($userExpenses) && $sumOfExpenses > 0) {
            foreach ($userExpenses as $expenseName => $amount) {
                $percent = $amount / $sumOfExpenses * 100;
                $expensesDataPoints[] = array("label" => $expenseName, "y" => $percent);
            }
        }

        $data = array(
            'incomes' => $userIncomes,
            'sumOfIncomes' => array_sum($userIncomes),
            'expenses' => $userExpenses,
            'sumOfExpenses' => array_sum($userExpenses),
            'choosenPerion' => $period,
            'incomeDataPoints' => $incomeDataPoints,
            'expenseDataPoints' => $expensesDataPoints
        );
        return $data;
    }

    /**
     * Set selected dates
     * 
     * @param string Contains selected period
     * 
     * @return array Contains earlier date and later date of selected period
     */
    public function finansesDates($period)
    {
        $dates = array(
            'earlierDate' => date("Y-m") . "-01",
            'laterDate' => date("Y-m") . "-31"
        );
        if ($period == "previousMonth") {
            if (date("m") == "01") {
                $month = "12";
                $year = date("Y") - 1;
            } else {
                $month = date("m") - 1;
                $year = date("Y");
            }
            $dates['earlierDate'] = $year . "-" . $month . "-01";
            $dates['laterDate'] = $year . "-" . $month . "-31";
        } else if ($period == "currentYear") {
            $dates['earlierDate'] = date("Y") . "-01-01";
            $dates['laterDate'] = date("Y") . "-12-31";
        } else if ($period == "other") {
            $dates['earlierDate'] = $_POST['beginingDate'];
            $dates['laterDate'] = $_POST['endingDate'];
        }
        return $dates;
    }
}
