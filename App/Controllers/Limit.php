<?php

namespace App\Controllers;

use \App\Models\Expense;

/**
 * Limit controller
 */
class Limit extends Authenticated
{
    /**
     * Get user expense category limit
     * 
     * @return void Category limit
     */
    public function expenseLimitAction()
    {
        $id = $this->route_params['id'];
        echo json_encode(Expense::getExpenseLimit($id), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get sum of one expenses category from chosen month 
     * 
     * @return void Sum of category expenses
     */
    public function categoryExpensesAction()
    {
        $id = $this->route_params['id'];
        $date = $this->route_params['date'];
        echo json_encode(Expense::getCategoryExpenses($id, $date), JSON_UNESCAPED_UNICODE);
    }
}
