<?php

namespace App\Controllers;

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
        echo "Witaj na stronie index kontrolera rejestracji!";
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
