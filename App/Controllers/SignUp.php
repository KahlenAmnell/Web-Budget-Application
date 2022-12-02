<?php

namespace App\Controllers;

use \Core\View;

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
        View::renderTemplate('Signup/index.html', [
            'name'  => 'Jan'
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
