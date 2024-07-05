<?php

namespace App\Core;

use App\Core\Request;

class Application
{
    private static $app;
    private RouteCollection $routes;
    private $request;

    /**
     * Initialize the application by creating the instance
     *
     * @return Application
     */
    public static function init(): Application
    {
        if (!self::$app) {
            self::$app = new self();
        }

        return self::$app;
    }

    /**
     * Run the application once everything is ready
     *
     * @param Request $request
     * @return void
     */
    public function run(Request $request): void
    {
        $this->request = $request;
        $this->handleRequest();
    }

    /**
     * Includes the application routes
     *
     * @param RouteCollection $routes
     * @return Application
     */
    public function withRoutes(RouteCollection $routes): Application
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Handle the incoming request
     *
     * @return void
     */
    private function handleRequest(): void
    {
        $method = $this->request->getMethod();
        $endpoint = $this->request->getRoutePath();
        $route = $this->findRoute($method, $endpoint);

        if(!$route){
            Response::redirect('/404');
        }

        $middlewares = $route->getMiddlewares();
        
        if($middlewares){
            $this->applyMiddlewares($middlewares);
        }

        $this->resolveAction($route->getAction());
    }

    /**
     * Find the route of the request
     *
     * @param string $method
     * @param string $endpoint
     * @return Route
     */
    private function findRoute(string $method, string $endpoint): Route
    {
        return $this->routes->find($method, $endpoint);
    }
    
    /**
     * Apply specified middleware to the request
     *
     * @param array $middlewares
     * @return void
     */
    private function applyMiddlewares(array $middlewares): void
    {
        foreach($middlewares as $middleware){
            $middleware::handle($this->request);
        }
    }

    /**
     * Resolve the action
     *
     * @param callable|array $action
     * @return void
     */
    private function resolveAction(callable|array $action): void
    {
        if (is_callable($action)) {
            $action($this->request);
        }

        elseif (is_array($action)) {
            $obj = new $action[0]();
            $obj->{$action[1]}($this->request);
        }
    }
}
