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
}
