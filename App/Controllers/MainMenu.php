<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;

/**
 * Main menu controller
 */

class MainMenu extends Authenticated
{
    /**
     * Show main menu 
     * 
     * @return void
     */
    public function indexAction()
    {
        $user = User::findByID($_SESSION['user_id']);
        $userIncomes = Income::getUserIncomes(0, date('Y-m-d'));
        $userExpenses = Expense::getUserExpenses(0, date('Y-m-d'));

        $sumOfIncomes = array_sum($userIncomes);
        $sumOfExpenses = array_sum($userExpenses);
        $incomeDataPoints = array();
        $expensesDataPoints = array();

        if (!empty($userIncomes) && $sumOfIncomes > 0) {
            foreach ($userIncomes as $incomeName => $amount) {
                $percent = $amount / $sumOfIncomes * 100;
                $incomeDataPoints[] = array("label" => $incomeName, "y" => $percent);
            }
        } else {
        }

        if (!empty($userExpenses) && $sumOfExpenses > 0) {
            foreach ($userExpenses as $expenseName => $amount) {
                $percent = $amount / $sumOfExpenses * 100;
                $expensesDataPoints[] = array("label" => $expenseName, "y" => $percent);
            }
        }

        View::renderTemplate('MainMenu/index.html', [
            'name' => $user->username,
            'sumOfIncomes' => $sumOfIncomes,
            'sumOfExpenses' => $sumOfExpenses,
            'incomeDataPoints' => $incomeDataPoints,
            'expenseDataPoints' => $expensesDataPoints
        ]);
    }
}
