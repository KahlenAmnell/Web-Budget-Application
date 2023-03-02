<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Categories;

/**
 * Account controller
 */
class Account extends \Core\Controller
{
    /**
     * Validate if email is available (AJAX) for a new signup.
     * 
     * @return void
     */
    public function validateEmailAction()
    {
        $is_valid = !User::emailExist($_GET['email'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    /**
     * Check if email already exist in database (AJAX).
     * 
     * @return void
     */
    public function checkEmailAction()
    {
        $is_valid = User::emailExist($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    /**
     * Check if category name already exist in database (AJAX)
     * 
     * @return void
     */
    public function checkCategoryNameAction()
    {
        $categories = new Categories($_GET);
        $is_available = $categories->checkName();

        header('Content-Type: application/json');
        echo json_encode($is_available);
    }
}
