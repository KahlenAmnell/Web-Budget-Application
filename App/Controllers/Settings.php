<?php

namespace App\Controllers;

use \Core\View;

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
        View::renderTemplate('Settings/editProfile.html');
    }
}