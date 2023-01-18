<?php

namespace App\Controllers;

use \App\Models\User;

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
        $is_valid = !User::emailExist($_GET['email']);

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
}
