<?php

/**
 * Front controller
 */

/**
 * Routing
 */
require '../Core/Router.php';

$router = new Core\Router();

echo get_class($router);
