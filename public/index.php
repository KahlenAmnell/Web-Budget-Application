<?php

/**
 * Front controller
 */

/**
 * Routing
 */
require '../Core/Router.php';

$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('posts/new', ['controller' => 'Posts', 'action' => 'new']);

$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParameters());
    echo '</pre>';
} else {
    echo "No route found for URL '$url'";
}
