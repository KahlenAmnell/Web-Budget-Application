<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Main menu controller
 */

class MainMenu extends Authenticated
{
    /**
     * Show main menu 
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('MainMenu/index.html', [
            'id' => $_SESSION['user_id']
        ]);
    }
}
