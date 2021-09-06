<?php

namespace Tahir\Router;

use Tahir\Middlewares\MiddlewareInterface;

class Router
{

    private $app;
    private $routes;
    private $middleware=[];
    private $notFound;
    private $grouPcontroller;
    private $grouPprefix;
    private $grouPmiddlewares=[];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function group($group,$callback)
    {
        $this->grouPprefix  = $group['prefix'];
        $this->grouPcontroller  = $group['controller'];
        $this->grouPmiddlewares = $group['middlewares'];

        call_user_func($callback,$this);

        return $this;
    }

    public function add($url,$action, $requestMethod = 'GET')
    {//dd($this->middleware);
        if($this->grouPprefix)
        {
            $url = rtrim($this->grouPprefix,'/').'/'.trim($url,'/');
        }

        if($this->grouPcontroller && !is_array($action) && !str_contains($action,'@') )
        {
            $action = [$this->grouPcontroller,$action];
        }

        $route = [
            'url'   => $url,
            'pattern'   => $this->generatePattern($url),
            'action'   => $this->getAction($action),
            'method'   => strtolower($requestMethod),
            'args' => $this->filterArgs($url)
        ];
        
        if($this->middleware)
        {
          $route['middleware'] = $this->middleware;
          
          $this->middleware = [];  
        }
        elseif($this->grouPmiddlewares && count($this->grouPmiddlewares) > 0)
        {
            $route['middleware'] = $this->grouPmiddlewares;
        }

        $this->routes[] = $route;

        return $this;
    }

    public function addFromArray($routes)
    {
        foreach($routes as $route)
        {

            $basRoute = [
                'url'           => $route['route'],
                'pattern'       => $this->generatePattern($route['route']),
                'action'        => $this->getAction($route['action']),
                'method'        => strtolower($route['method']),
                'args'          => $this->filterArgs($route['route']),
                'middleware'    => $route['middelwares'],
            ];

            $this->routes[] = $basRoute;
             
        }
        return $this;
    }

    public function middlewares(array $middlewares)
    {
        $this->middleware = $middlewares;
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
                $output = '';

                if(isset($route['middleware']) && count($route['middleware']) > 0)
                {
                    foreach($route['middleware'] as $middleware)
                    {
                        $middlewareObj = new $middleware();

                        if($middlewareObj instanceof MiddlewareInterface);
                        {
                            $output = $middlewareObj->handle($this->app,'next');

                            if($output == 'next'){$output = '';}
                            else{break;}
                        }
                    }
                }

                if($output == '')
                {
                    $args = $this->getArgsFrom($route['pattern'],$route['args']);

                    $actionAndController = $route['action'];

                    if(!is_array($route['action']))
                    {
                        $actionAndController = explode('@', $route['action']);
                    }

                    list($controller, $action) = $actionAndController;

                    $output = $this->app->load->action($controller, $action, $args);
                }
                

                return $output;
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
        if(!is_array($action))
        {
          $action = str_replace('/', '\\', $action);  
        }
        else
        {
            $action[1] = str_replace('/', '\\', $action[1]);
        }

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