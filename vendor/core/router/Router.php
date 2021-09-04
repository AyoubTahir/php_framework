<?php

namespace Tahir\Router;

class Router
{

    private $app;
    private $routes;
    private $notFound;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function add($url,$action, $requestMethod = 'GET')
    {
        

        $route = [
            'url'   => $url,
            'pattern'   => $this->generatePattern($url),
            'action'   => $this->getAction($action),
            'method'   => strtolower($requestMethod),
            'args' => $this->filterArgs($url)
        ];

        $this->routes[] = $route;
    }

    public function addFromArray($routes)
    {
        foreach($routes as $controller => $details)
        {
            foreach($details as $url => $methods)
            {
                $route = [
                    'url'   => $url,
                    'pattern'   => $this->generatePattern($url),
                    'action'   => $this->getAction($controller . '@' . $methods[0]),
                    'method'   => strtolower($methods[1]),
                    'args' => $this->filterArgs($url)
                ];

                $this->routes[] = $route;
            }
             
        }
        return $this;
    }

    public function notFound($url)
    {
        $this->notFound = $url;
    }

    public function getProperRoute()
    {
        foreach($this->routes as $route)
        {
            if($this->match($route['pattern']) && $route['method'] == $this->app->request->method() )
            {
                $args = $this->getArgsFrom($route['pattern'],$route['args']);

                list($controller, $action) = explode('@', $route['action']);

                return [$controller, $action, $args];
            }
        }
        dd('not found');
    }

    private function match($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }

    private function getArgsFrom($pattern,$args)
    {
        preg_match($pattern, $this->app->request->url(),$m);

        array_shift($m);

        return array_combine($args, $m);
    }

    private function generatePattern($url)
    {
        $argsKeys = $this->filterArgs($url,false);

        $pattern = '#^';

        $pattern .= str_replace($argsKeys,'([a-zA-Z0-9-]+)',$url);

        $pattern .= '$#';

        return $pattern;
    }

    private function getAction($action)
    {
        $action = str_replace('/', '\\', $action);

        return $action;
    }

    private function filterArgs($url,$dontRemove = true,$with = ':')
    {
        $args = [];

        
        foreach(explode('/',$url) as $value)
        {
            if(str_contains($value, $with))
            {
                if($dontRemove === true)
                {
                    $args[] = str_replace($with,'',$value);
                }
                else
                {
                    $args[] = $value;
                }
                
            }
        } 

        return $args;
    }

}