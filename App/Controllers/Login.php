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
        $remember_me = isset($_POST['remember_me']);

        if ($user && ($user->is_active == 1)) {
            Auth::login($user, $remember_me);
            $this->redirect(Auth::getReturnToPage());
        } else {
            View::renderTemplate(('Login/index.html'), [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
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
