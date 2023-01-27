<?php

namespace App\Controllers;

use App\Models\Expense;
use \Core\View;
use \App\Models\Income;
use \App\Dates;
use \App\Models\User;

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
        $period = User::setBalancePeriod();
        $dates = Dates::finansesDates($period);
        $userIncomes = Income::getUserIncomes($dates['earlierDate'], $dates['laterDate']);
        $userExpenses = Expense::getUserExpenses($dates['earlierDate'], $dates['laterDate']);
        $sumOfIncomes = array_sum($userIncomes);
        $sumOfExpenses = array_sum($userExpenses);
        $incomeDataPoints = User::createChartData($userIncomes, $sumOfIncomes);
        $expensesDataPoints = User::createChartData($userExpenses, $sumOfExpenses);
        $incomeList = Income::getIncomesList($dates['earlierDate'], $dates['laterDate']);
        $expenseList = Expense::getExpensesList($dates['earlierDate'], $dates['laterDate']);

        View::renderTemplate('Balance/index.html', [
            'incomes' => $userIncomes,
            'sumOfIncomes' => array_sum($userIncomes),
            'expenses' => $userExpenses,
            'sumOfExpenses' => array_sum($userExpenses),
            'choosenPerion' => $period,
            'incomeDataPoints' => $incomeDataPoints,
            'expenseDataPoints' => $expensesDataPoints,
            'incomeList' => $incomeList,
            'expenseList' => $expenseList
        ]);
    }
}
