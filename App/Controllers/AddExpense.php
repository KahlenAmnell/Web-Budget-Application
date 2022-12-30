<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Flash;
use \App\Auth;

/**
 * Add expense controller
 */
class AddExpense extends Authenticated
{
    /**
     * Show add income page
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('AddExpense/index.html', [
            'expenseCategories' => Expense::getExpenseCategories(),
            'paymentCategories' => Expense::getPaymentCategories()
        ]);
    }

    /**
     * Add expense to base
     * 
     * @return void
     */
    public function createAction()
    {
        $expense = new Expense($_POST);

        if ($expense->save()) {
            Flash::addMessage('Dodano wydatek.');
            $this->redirect('/add-expense/index');
        } else {
            Flash::addMessage('Nie udało się dodać wydatku.', 'danger');
            View::renderTemplate('AddExpense/index.html', [
                'expense' => $expense
            ]);
        }
    }
}
