<?php

namespace App\Core;

class RouteCollection {
    private $routes;

    /**
     * Constructor
     *
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Find a route by method and endpoint
     *
     * @param string $method
     * @param string $endpoint
     * @return Route|null
     */
    public function find(string $method, string $endpoint): Route|null
    {
        return isset($this->routes[$method][$endpoint]) ? $this->routes[$method][$endpoint] : null;
    }
}