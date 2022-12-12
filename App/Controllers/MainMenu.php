<?php

namespace App\Controllers;

use \Core\View;

/**
 * Main menu controller
 */

class MainMenu extends \Core\Controller
{
    /**
     * Show main menu 
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('MainMenu/index.html');
    }
}
