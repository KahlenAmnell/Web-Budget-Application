<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

/**
 * Login controller
 */

class Login extends \Core\Controller
{
    /**
     * Show the login page
     * 
     * @return void
     */
    public function indexAction()
    {
        if (Auth::getUser()) {
            $this->redirect('/main-menu/index');
        }
        View::renderTemplate('Login/index.html');
    }

    /**
     * Log in a user
     * 
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        if ($user) {
            $user->username = Auth::getReturnToPage();
            Auth::login($user);
            $this->redirect(Auth::getReturnToPage());
        } else {
            View::renderTemplate(('Login/index.html'), [
                'email' => $_POST['email']
            ]);
        }
    }

    /**
     * Log out a user
     * 
     * @return void
     */
    public function logOutAction()
    {
        Auth::logout();
        $this->redirect('/');
    }
}
