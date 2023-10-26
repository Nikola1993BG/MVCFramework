<?php

namespace App\Core;

use App\Core\View;
use App\Core\Request;
class Router{

    private array $routes;
    

    public function __construct()
    {
        
    }

    /**
     * Registers a GET route with the given path and callable.
     *
     * @param string $path The path of the route.
     * @param mixed $callable The callable to be executed when the route is matched.
     * @return void
     */
    public function get(string $path, mixed $callable) : void
    {
        $this->routes['get'][$path] = $callable;
    }

    /**
     * Add a new POST route to the router.
     *
     * @param string $path The URL path of the route.
     * @param mixed $callable The callback function or method to execute when the route is matched.
     * @return void
     */
    public function post(string $path, mixed $callable) :void
    {
        $this->routes['post'][$path] = $callable;
    }

    /**
     * Resolves the current request by matching the requested path and method to a registered route.
     * If a matching route is found, the corresponding callback is executed.
     * If no matching route is found, a 404 error page is rendered.
     *
     * @return void
     * @throws \Exception if the callback method specified in the route is not found in the corresponding controller class
     */
    public function resolve():void
    {
        $path = Request::getPath();
        $method = Request::getMethod();

        $callback=null;
        $params=null;
        foreach($this->routes[$method] as $pattern=>$value){
            
            $pattern = preg_quote($pattern, '~');
            $pattern = preg_replace('/(?<!\\\\)\\\\\*/', '.*?', $pattern);
            $pattern = preg_replace_callback('%@([a-zA-Z]+)(\\\\:([^/]+))?%', 
                function($data){
                    $pattern = '[^/]+';
                    if(!empty($data[3])) {
                        $pattern = stripslashes($data[3]);
                    }
                    return '(?P<' . $data[1] . '>' . $pattern . ')';                    
                }, 
            $pattern);
            $pattern = '~^' . $pattern . '$~';
            if(preg_match($pattern, $path, $matches)) {
                $params = $matches;
                $callback = $value;
                break;
            }

        }
        
        if($callback === NULL){
            View::renderTwig('404.html');
        }

        if(is_callable($callback)) {
            call_user_func($callback);
        }
        else if(is_string($callback)){
            View::renderTwig($callback);
        }
        else if(is_array($callback)){

            $controllerClass = $callback[0];
            $method = $callback[1];

            if(!method_exists($controllerClass , $method)) {
                throw new \Exception('Method '.$method. ' not found in '.$controllerClass);
            }

            call_user_func([new $controllerClass, $method], array_merge($params, []));
        }
        
    }
}