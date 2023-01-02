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
        $userIncomes = Income::getUserIncomes('2022-12-01', '2022-12-31');
        $userExpenses = Expense::getUserExpenses('2022-12-01', '2022-12-31');
        View::renderTemplate('Balance/index.html', [
            'incomes' => $userIncomes,
            'sumOfIncomes' => array_sum($userIncomes),
            'expenses' => $userExpenses,
            'sumOfExpenses' => array_sum($userExpenses)
        ]);
    }
}
