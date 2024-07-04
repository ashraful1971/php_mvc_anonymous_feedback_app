<?php

namespace App\Core;

use App\Core\Request;

class Application
{
    private static $app;
    private RouteCollection $routes;
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

    private function handleRequest()
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

        return $this->resolveAction($route->getAction());
    }

    private function findRoute($method, $endpoint)
    {
        return $this->routes->find($method, $endpoint);
    }
    
    private function applyMiddlewares($middlewares)
    {
        foreach($middlewares as $middleware){
            $middleware::handle($this->request);
        }
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
