<?php

/**
 * Front controller
 */

/**
 * Sign up controller
 */
require '../App/Controllers/SignUp.php';

/**
 * Routing
 */
require '../Core/Router.php';


$router = new Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
