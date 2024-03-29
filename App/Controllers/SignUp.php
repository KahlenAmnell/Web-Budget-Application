<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

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
        if (Auth::getUser()) {
            $this->redirect('/main-menu/index');
        }
        View::renderTemplate('Signup/index.html');
    }

    /**
     * Sign up new user
     * 
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $user->sendActivationEmail();
            $this->redirect('/sign-up/success');
        } else {
            View::renderTemplate('Signup/index.html', [
                'user' => $user
            ]);
        }
    }

    /**
     * Show the success page
     * 
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }

    /**
     * Activate a new account
     * 
     * @return void
     */
    public function activateAction()
    {
        User::activate($this->route_params['token']);

        $this->redirect('/sign-up/activated');
    }

    /**
     * Show the activation success page
     * 
     * @return void
     */
    public function activatedAction()
    {
        View::renderTemplate('Signup/activated.html');
    }
}
