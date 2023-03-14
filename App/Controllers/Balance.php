<?php

namespace App\Controllers;

use App\Models\Expense;
use \Core\View;
use \App\Models\Income;
use \App\Dates;
use App\Models\Finances;
use \App\Models\User;
use \App\Flash;

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
            'expenseList' => $expenseList,
            'incomeCategories' => Income::getIncomeCategories(),
            'expenseCategories' => Expense::getExpenseCategories(),
            'paymentCategories' => Expense::getPaymentCategories()
        ]);
    }

    public function deleteIncomeAction()
    {
        Finances::deleteRecord($this->route_params['id'], 'incomes');
        Flash::addMessage('Usunięto przychód');
        $this->redirect('/balance');
    }

    public function deleteExpenseAction()
    {
        Finances::deleteRecord($this->route_params['id'], 'expenses');
        Flash::addMessage('Usunieto wydatek');
        $this->redirect('/balance');
    }

    public function updateIncomeAction()
    {
        $update = new Income($_POST);
        if($update->updateRecord())
        {
            Flash::addMessage('Zaktualizowano');
            $this->redirect('/balance');
        } else {
            Flash::addMessage('Nie udało się wprowadzić zmian');
            $this->redirect('/balance');
        }  
    }

    public function updateExpenseAction()
    {
        $update = new Expense($_POST);
        if($update->updateRecord())
        {
            Flash::addMessage('Zaktualizowano');
            $this->redirect('/balance');
        } else {
            Flash::addMessage('Nie udało się wprowadzić zmian');
            $this->redirect('/balance');
        }  
    }
}
