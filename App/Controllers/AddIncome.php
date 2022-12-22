<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Flash;
use \App\Auth;

/**
 * Add income controller
 */
class AddIncome extends Authenticated
{
    /**
     * Show add income page
     * 
     * @return void
     */
    public function indexAction()
    {
        $user = Auth::getUser();
        View::renderTemplate('AddIncome/index.html', [
            'categories' => Income::getIncomeCategories()
        ]);
    }

    /**
     * Add income to base
     * 
     * @return void
     */
    public function createAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {
            Flash::addMessage('Dodano przychód.');
            $this->redirect('/add-income/index');
        } else {
            Flash::addMessage('Nie udało się dodać przychodu.', 'danger');
            View::renderTemplate('AddIncome/index.html', [
                'income' => $income
            ]);
        }
    }
}
