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
    /**
     * Before filter - called before each action method
     * 
     * @reeturn void 
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Settings/index.html');
    }
    /**
     * Show the form for editing the profile
     * 
     * @return void
     */
    public function editProfileAction()
    {
        View::renderTemplate('Settings/editProfile.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     * 
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {
            Flash::addMessage('Zapisano zmiany');
            $this->redirect('/settings/index');
        } else {
            View::renderTemplate('Settings/editProfile.html', [
                'user' => $this->user
            ]);
        }
    }
}
