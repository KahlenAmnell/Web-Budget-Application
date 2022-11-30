<?php

namespace App\Controllers;

/**
 * Sign Up controller
 */

class Signup
{
    /**
     * Show the index page
     * 
     * @return void
     */
    public function index()
    {
        echo "Witaj na stronie index kontrolera rejestracji!";
    }

    /**
     * Show the success page
     * 
     * @return void
     */
    public function success()
    {
        echo "Witaj na stronie szczęśliwie zarejestrowanych użytkowników kontrolera rejestracji!";
    }
}
