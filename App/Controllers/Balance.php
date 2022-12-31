<?php

namespace App\Controllers;

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
        View::renderTemplate('Balance/index.html', [
            'incomes' => $userIncomes,
            'sumOfIncomes' => $this->sum($userIncomes)
        ]);
    }

    /**
     * Sum user finances
     * 
     * @param array Table with user category and amount
     * 
     * @return int Sum of array
     */
    public function sum($userData)
    {
        $sum = array_sum($userData);
        foreach ($userData as $child) {
            $sum += is_array($child) ? $this->sum($child) : 0;
        }
        return $sum;
    }
}
