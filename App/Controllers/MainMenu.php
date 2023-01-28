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
        $incomeDataPoints = User::createChartData($userIncomes, $sumOfIncomes);
        $expensesDataPoints = User::createChartData($userExpenses, $sumOfExpenses);

        View::renderTemplate('MainMenu/index.html', [
            'name' => $user->username,
            'sumOfIncomes' => $sumOfIncomes,
            'sumOfExpenses' => $sumOfExpenses,
            'incomeDataPoints' => $incomeDataPoints,
            'expenseDataPoints' => $expensesDataPoints
        ]);
    }
}
