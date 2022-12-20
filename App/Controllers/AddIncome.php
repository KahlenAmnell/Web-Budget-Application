<?php

namespace App\Controllers;

use \Core\View;

/**
 * Add income controller
 */
class AddIncome extends Authenticated
{
/**
     * Show add income page
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('AddIncome/index.html');
    }
}