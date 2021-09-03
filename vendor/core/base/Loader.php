<?php

namespace Tahir\Base;

use \Exception;
use ReflectionMethod;

class Loader
{

    private $app;
    private $controllers = [];
    private $models = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function action($controller, $action, array $args)
    {
        $controllerObject = $this->controller($controller);

        $args = $this->setTheVerifiedParam($controllerObject,$action,$args);

        return call_user_func_array([$controllerObject, $action], $args);
    }

    public function getControllerName($controller)
    {
        return 'App\\' . $controller;
    }

    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);

        if( !$this->hasController($controller) )
        {
            $this->addController($controller);
        }

        return $this->getController($controller);
    }

    private function hasController($controller)
    {
        return array_key_exists($controller,$this->controllers);
    }

    public function addController($controller)
    {

        $objectController = new $controller($this->app);

        $this->controllers[$controller] = $objectController;
    }

    public function getController($controller)
    {
        return $this->controllers[$controller];
    }



    public function getModelName($model)
    {
        $model = 'App\\' . $model . 'Model';

        return str_replace('/','\\',$model);
    }

    public function model($model)
    {
        $model = $this->getModelName($model);

        if( !$this->hasModel($model) )
        {
            $this->addModel($model);
        }

        return $this->getModel($model);
    }

    private function hasModel($model)
    {
        return array_key_exists($model,$this->models);
    }

    public function addModel($model)
    {
        $objectModel = new $model($this->app);

        $this->models[$model] = $objectModel;
    }

    public function getModel($model)
    {
        return $this->models[$model];
    }

    private function setTheVerifiedParam($controller,$action,$args)
    {
        $args = $this->addRequest($args);

        $refMeth = new ReflectionMethod($controller::class,$action);

        $actionParams =[];

        foreach ( $refMeth->getParameters() as $param )
        {

            $actionParams[] = $param->getName();   
        }

        if( count($actionParams) > count($args))
        {
            throw new Exception('you have sepcify a wrong prameters in your controller at '.$action);
        }

        $matchedParams = [];

        foreach($args as $key=>$param)
        {
            if( in_array( $key,array_values( $actionParams ) ) )
            {
                $matchedParams[$key] = $param;
            }
        }

        return $matchedParams;
    }

    private function addRequest($args)
    {
        if($this->app->request->hasAnyRequest())
        {
            $args['request'] = $this->app->request;
        }

        return $args;
    }

}