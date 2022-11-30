<?php

/**
 * Base controller
 */

 abstract class Controller
 {
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Class contructor
     * 
     * @param array $route_params Parameters from the route
     * 
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }
}