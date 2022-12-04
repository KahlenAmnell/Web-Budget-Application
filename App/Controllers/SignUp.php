<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Sign Up controller
 */

class Signup extends \Core\Controller
{
    /**
     * Show the index page
     * 
     * @return void
     */
    public function indexAction()
    {
        $base = User::test();

        View::renderTemplate('Signup/index.html', [
            'name'  => 'Jan',
            'base' => $base
        ]);
    }

    /**
     * Show the success page
     * 
     * @return void
     */
    public function successAction()
    {
        echo "Witaj na stronie szczęśliwie zarejestrowanych użytkowników kontrolera rejestracji!";
    }
}
