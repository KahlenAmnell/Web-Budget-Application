<?php

namespace App\Controllers;

use \Core\View;

/**
 * Balance controller
 */
class Balance extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Balance/index.html');
    }
}