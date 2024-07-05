<?php

namespace App\Core;

class Route {
    private static $routes;
    private $method;
    private $endpoint;
    private $action;
    private $middlewares;

    // Request Method Constants
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';

    /**
     * Constructor to init the props
     *
     * @param string $method
     * @param string $endpoint
     * @param callable|array $action
     * @param array $middlewares
     */
    public function __construct(string $method, string $endpoint, callable|array $action, array $middlewares)
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->action = $action;
        $this->middlewares = $middlewares;
    }

    /**
     * Register a route with method type as GET
     *
     * @param string $endpoint
     * @param callable|array $action
     * @param array $middlewares
     * @return void
     */
    public static function get(string $endpoint, callable|array $action, array $middlewares=[]): void
    {
        self::$routes[self::GET][$endpoint] = new self(self::GET, $endpoint, $action, $middlewares);
    }
    
    /**
     * Register a route with method type as POST
     *
     * @param string $endpoint
     * @param callable|array $action
     * @param array $middlewares
     * @return void
     */
    public static function post($endpoint, $action, $middlewares=[]): void
    {
        self::$routes[self::POST][$endpoint] = new self(self::POST, $endpoint, $action, $middlewares);
    }
    
    /**
     * Get the method for the route
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    
    /**
     * Get the route endpoint
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    
    /**
     * Get the action for the route
     *
     * @return callable|array
     */
    public function getAction(): callable|array
    {
        return $this->action;
    }

    /**
     * Get the middlewares for the route
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
    
    /**
     * Get the collection of routes
     *
     * @return RouteCollection
     */
    public static function collection(): RouteCollection
    {
        return new RouteCollection(self::$routes);
    }
}