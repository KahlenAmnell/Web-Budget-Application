<?php

/**
 * Front controller
 */

ini_set('session.cookie_lifetime', 864000); // ten days in seconds

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Sessions
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

$router->add('', ['controller' => 'Login', 'action' => 'index']);
$router->add('logout', ['controller' => 'Login', 'action' => 'log-out']);
$router->add('sign-up/activate/{token:[\da-f]+}', ['controller' => 'SignUp', 'action' => 'activate']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
