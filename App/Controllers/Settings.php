<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Categories;
use \App\Models\Income;
use \App\Models\Expense;

/**
 * Settings controller
 */
class Settings extends Authenticated
{
    /**
     * Before filter - called before each action method
     * 
     * @reeturn void 
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Settings/index.html');
    }
    /**
     * Show the form for editing the profile
     * 
     * @return void
     */
    public function editProfileAction()
    {
        View::renderTemplate('Settings/editProfile.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     * 
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/settings/index');
        } else {
            View::renderTemplate('Settings/editProfile.html', [
                'user' => $this->user
            ]);
        }
    }

    /**
     * Show the add cattegory page
     * 
     * @return void
     */
    public function addCategoryAction()
    {
        View::renderTemplate('Settings/addCategory.html');
    }

    /**
     * Add new category
     * 
     * @return void
     */
    public function newCategoryAction()
    {
        $category = new Categories($_POST);

        if ($category->save()) {
            Flash::addMessage('Dodano nową kategorię.');
            $this->redirect('/settings/add-category');
        } else {
            Flash::addMessage('Nie udało się dodać kategorii.', 'danger');
            View::renderTemplate('Settings/addCategory.html', [
                'category' => $category
            ]);
        }
    }

    /**
     * Edit categories
     * 
     * @return void
     */
    public function editCategoriesAction()
    {
        $incomeCategories = Income::getIncomeCategories();
        $expenseCategories = Expense::getExpenseCategories();
        $paymentCategories = Expense::getPaymentCategories();
        
        View::renderTemplate('Settings/editCategory.html', [
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'paymentCategories' => $paymentCategories
        ]);
    }

    /**
     * Update categories
     * 
     * @return void
     */
    public function updateCategoriesAction()
    {
        $category = new Categories($_POST);

        if ($category->update()) {
            Flash::addMessage('Zedytowano kategorię.');
            $this->redirect('/settings/edit-categories');
        } else {
            Flash::addMessage('Nie udało się zedytować kategorii.', 'danger');
            View::renderTemplate('Settings/addCategory.html');
        }
    }

    /**
     * Delete categories
     * 
     * @return void
     */
    public function deleteCategoriesAction()
    {
        Categories::deleteCategory($this->route_params['categorygroup'], $this->route_params['id']);
        Flash::addMessage('Usunięto kategorię');
        $this->redirect('/settings/edit-categories');
    }
}
