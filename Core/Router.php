<?php

//namespace Core;

/**
 * Router
 */

class Router
{
    /**
     * Associative array of routes
     * 
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from the matched route
     * 
     * @var array
     */
    protected $parameters = [];

    /**
     * Add a route to the routing table
     * 
     * @param string $route     The route URL
     * @param array $parameters Parameters
     * 
     * @return void 
     */
    public function add($route, $parameters = [])
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $parameters;
    }

    /**
     * Get all routes from the routing table
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $parameters
     * property if the route is found
     * 
     * @param string $url   The route URL
     * 
     * @param boolean true if a match found, false otherwise
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $parameters) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $parameters[$key] = $match;
                    }
                }
                $this->parameters = $parameters;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the matched parameters
     * 
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Dispatch the route, creating the controller object and running the action method
     * 
     * @param string $url The route URL
     * 
     * @return void
     */
    public function dispatch($url)
    {
        if ($this->match($url)) {
            $controller = $this->parameters['controller'];
            $controller = $this->convertToStudlyCaps($controller);

            if (class_exists($controller)) {
                $controller_object = new $controller();

                $action = $this->parameters['action'];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    echo "Method $action (in controller $controller) not found";
                }
            } else {
                echo "Controller class $controller not found";
            }
        } else {
            echo "No route matched";
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps, e.g. add-income => AddIncome
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase, e.g. add-new-income => addNewIncome
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
}
