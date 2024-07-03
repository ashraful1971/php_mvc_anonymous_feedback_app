<?php

namespace App\Core;

class Route {
    private static $routes;
    private $method;
    private $endpoint;
    private $action;
    private $middlewares;

    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';

    public function __construct($method, $endpoint, $action, $middlewares)
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->action = $action;
        $this->middlewares = $middlewares;
    }

    public static function get($endpoint, $action, $middlewares=[])
    {
        self::$routes[self::GET][$endpoint] = new self(self::GET, $endpoint, $action, $middlewares);
    }
    
    public static function post($endpoint, $action, $middlewares=[])
    {
        self::$routes[self::POST][$endpoint] = new self(self::POST, $endpoint, $action, $middlewares);
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getEndpoint()
    {
        return $this->endpoint;
    }
    
    public function getAction()
    {
        return $this->action;
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }
    
    public static function collection()
    {
        return new RouteCollection(self::$routes);
    }
}