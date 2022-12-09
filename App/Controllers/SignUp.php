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
     * Sign up new user
     * 
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $this->redirect('/signup/success');
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
}
