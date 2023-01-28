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
}