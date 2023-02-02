<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Settings controller
 */
class Settings extends Authenticated
{
    public function indexAction()
    {
        View::renderTemplate('Settings/index.html');
    }

    public function editProfileAction()
    {
        View::renderTemplate('Settings/editProfile.html', [
            'user' => Auth::getUser()
        ]);
    }

    public function updateAction()
    {
        $user = Auth::getUser();
        var_dump('tu');
        if($user->updateProfile($_POST)){
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/settings/index');
        } else {
            View::renderTemplate('Settings/editProfile.html', [
                'user' => $user
            ]);
        }
    }
}
