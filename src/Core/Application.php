<?php

namespace App\Core;

use App\Core\Request;

class Application
{
    private static $app;
    private RouteCollection $routes;
    private $middlewares;
    private $request;

    public static function init(): Application
    {
        if (!self::$app) {
            self::$app = new self();
        }

        return self::$app;
    }

    public function run(Request $request)
    {
        $this->request = $request;
        return $this->handleRequest();
    }

    public function withRoutes(RouteCollection $routes)
    {
        $this->routes = $routes;

        return $this;
    }

    public function withMiddlewares($middlewares)
    {
        return $this->middlewares = $middlewares;
    }

    private function handleRequest()
    {
        $method = $this->request->getMethod();
        $endpoint = $this->request->getRoutePath();
        $action = $this->getAction($method, $endpoint);
        $this->resolveAction($action);
    }

    private function findRoute($method, $endpoint)
    {
        return $this->routes->find($method, $endpoint);
    }

    private function getAction($method, $endpoint)
    {
        $route = $this->findRoute($method, $endpoint);
        return $route->getAction();
    }

    private function resolveAction($action)
    {
        if (is_callable($action)) {
            return $action($this->request);
        }

        if (is_array($action)) {
            $obj = new $action[0]();
            $obj->{$action[1]}($this->request);
        }
    }
}
