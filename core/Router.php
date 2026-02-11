<?php

namespace app\core;

class Router
{

    protected array $routes = [];
    public function get($path, $callback) // Accepts path and callback function
    {
        $this->routes['get'][$path] = $callback;
//        Example:
//        'get' => [
//            '/' => $callback
//        ]
    }
    
}