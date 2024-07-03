<?php

namespace App\Core;

class Request {
    private $attributes = [];
    private $method;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function __get($name)
    {
        if(isset($_POST) && isset($_POST[$name])){
            return $_POST[$name];
        }
        
        if(isset($_GET) && isset($_GET[$name])){
            return $_GET[$name];
        }
        
        if(isset($this->attributes[$name])){
            return $this->attributes[$name];
        }
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getMethod()
    {
        return $this->method;
    }
    
    public function getURI()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $base = dirname($_SERVER['SCRIPT_NAME']);
        $base = str_replace("\\", '/', $base);
        if($base !== '/'){
            $uri = explode($base, $uri)[1];
        }

        return $uri;
    }
    
    public function getRoutePath()
    {
        $path = parse_url(self::getURI())['path'];
        return $path;
    }
    
    public function all()
    {
        return $this->getMethod() === 'POST' ? $_POST : $_GET;
    }
}