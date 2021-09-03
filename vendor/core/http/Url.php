<?php

namespace Tahir\Http;

class Url
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function link($path)
    {
        return $this->app->request->baseUrl() . trim($path, '/');
    }

    public function redirectTo($path)
    {
        header('location:' . $this->link($path));
        exit;
    }

    public function addMessage($key,$message)
    {
        $this->app->session->setFlash($key,$message);
        
        return $this;
    }




}