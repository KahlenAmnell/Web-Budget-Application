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
}
