<?php

namespace App\Core;

class RouteCollection {
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function find($method, $endpoint): Route
    {
        return $this->routes[$method][$endpoint];
    }
}