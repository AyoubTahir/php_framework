<?php

namespace Tahir\Base;

abstract class Controller
{

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        foreach($this->models as $model => $path)
        {
            $this->$model = $this->load->model($path);
        }
    }

    public function __get($key)
    {
        return $this->app->get($key);
    }

}