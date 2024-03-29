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
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('api/expenseLimit/{id:\d+}', ['controller' => 'Limit', 'action' => 'expenseLimit']);
$router->add('api/categoryExpenses/{id:\d+}/{date:(\d\d\d\d)-(\d\d)-(\d\d)}', ['controller' => 'Limit', 'action' => 'categoryExpenses']);
$router->add('settings/delete-categories/{categorygroup:[a-z]+}/{id:\d+}', ['controller' => 'Settings', 'action' => 'deleteCategories']);
$router->add('{controller}', ['action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
