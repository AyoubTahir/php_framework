<?php

namespace Tahir\Http;

class Request
{

    private $app;
    private $url;
    private $baseUrl;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function prepareUrl()
    {
        $script         = str_replace('public', '', dirname($this->server('SCRIPT_NAME')));
        $request_uri    = $this->server('REQUEST_URI');

        if(strpos($request_uri, '?') != null)
        {
            $request_uri = explode('?', $request_uri )[0];
        }
        
        $this->url =  $request_uri;
        
        if($script != '/')
        {
            $this->url = '/' . str_replace($script, '', $request_uri);
        }

        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script;  
    }

    public function server($key, $default = null)
    {
        return array_get($_SERVER, $key, $default);
    }
    
    public function url()
    {
        return $this->url;
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }

    public function currentUrl()
    {
        return $this->baseUrl() . trim($this->url(),'/');
    }

    public function cleanGet()
    {
        $get = $_GET;
        array_shift($get);
        return $get;
    }

    public function cleanPost()
    {
        return $_POST;
    }

    public function get($key, $default = null)
    {
        return array_get($this->cleanGet(), $key, $default);
    }

    public function post($key, $default = null)
    {
        return array_get($this->cleanPost(), $key, $default);
    }

    public function method($key, $default = null)
    {
        return $this->server('REQUEST_METHOD');
    }

    public function ArrNotEmpty($array)
    {
        return $this->app->arr->has($array);
    }

    public function hasAnyRequest()
    {
        if($this->ArrNotEmpty($this->cleanGet()))
        {
            return true;
        }
        if($this->ArrNotEmpty($this->cleanPost()))
        {
            return true;
        }

        return false;
    }

    public function has($key)
    {
        return isset($this->all()[$key]);
    }

    public function all()
    {
        if($this->ArrNotEmpty($this->cleanGet()))
        {
            return $this->cleanGet();
        }
        if($this->ArrNotEmpty($this->cleanPost()))
        {
            return $this->cleanPost();
        }

        return [];
    }

    public function only($keys)
    {
        if($this->ArrNotEmpty($this->cleanGet()))
        {
            return $this->app->arr->only($this->cleanGet(), $keys);
        }
        if($this->ArrNotEmpty($this->cleanPost()))
        {
            return $this->app->arr->only($this->cleanPost(), $keys);
        }

        return [];
    }

    public function except($keys)
    {
        if($this->ArrNotEmpty($this->cleanGet()))
        {
            return $this->app->arr->except($this->cleanGet(), $keys);
        }
        if($this->ArrNotEmpty($this->cleanPost()))
        {
            return $this->app->arr->except($this->cleanPost(), $keys);
        }

        return [];
    }

    public function validate(array $rules)
    {
        if( $this->hasAnyRequest() )
        {
            $validate = $this->app->validator->setData($this->all())->validate($rules);
            
            if(!$validate->passes())
            {
                $this->app->session->setFlash('errors',$validate->errors());

                header('Location:' . $_SERVER['HTTP_REFERER']);
                
                exit;
            }
        }
        
        return $this;
    }

    public function store($input,$path,$fileName = null)
    {
        return $this->app->storage->file($input)->storage($path,$fileName);
    }
    
}