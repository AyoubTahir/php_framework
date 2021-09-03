<?php

namespace Tahir\Base;

use Tahir\File\File;

class Application
{
    private $container = [];

    private static $instance;

    private function __construct(File $file)
    {
        $this->errorsHandller();

        $this->share('file', $file);

        $this->session->set('lang', 'ar');//env('DEFAULT_LANG','ar')

        $this->loadHelpers();
    }

    public static function getInstance($file = null)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static($file);
        }

        return static::$instance;
    }

    public function errorsHandller()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    public function share($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function isShared($key)
    {
        return isset($this->container[$key]);
    }

    public function get($key)
    {
        if(!$this->isShared($key))
        {
            if($this->isSystemHave($key))
            {
                $this->share($key, $this->newSystemObject($key));
            }
            else
            {
                trigger_error('cant instance new '.$key, E_USER_ERROR);
            }
        }

        return $this->container[$key];
    }

    public function isSystemHave($key)
    {
        return isset($this->systemClasses()[$key]);
    }

    public function newSystemObject($key)
    {
        $classString = $this->systemClasses()[$key];

        return new $classString($this);
    }

    public function systemClasses()
    {
        return require($this->file->toVendor('base/systemClasses.php'));
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function loadHelpers()
    {
        $this->file->callFromVendor('support/helpers');
    }

    public function run()
    {

        $this->request->prepareUrl();

        list($controller, $action, $args) = $this->changeRoutingMethod();

        $output = $this->load->action($controller, $action, $args);

        if(! empty($output))
        {
            $this->response->setOutput($output);

            $this->response->send();
        }
        
        /*
        if(is_array($output))
        {
            $this->restFul->response($output,200);
        }
        else
        {
            echo (string) $output;
        }*/

        //dd($this->scan->scanDir('app','languages')->filesWithPath());

        //$this->migration->applyMigrations();
    }

    private function changeRoutingMethod($routingMethod = 'array')
    {
        $arr = [];

        if($routingMethod == 'file')
        {
            $this->file->call('routes/routes');
            $arr = $this->router->getProperRoute();
        }
        elseif($routingMethod == 'array')
        {
            $routes = require($this->file->to('routes/routess.php'));
            $arr = $this->router->addFromArray($routes)->getProperRoute();
        }
        
        return $arr;
    }

    public function runMigration()
    {
        $this->migration->applyMigrations();
    }
}