<?php

if( !function_exists('pre') )
{
    function pre($value)
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}

if( !function_exists('array_get') )
{
    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('env'))
{
    function env($key, $default = null)
    {
        return $_ENV[$key] ? $_ENV[$key] : $default;
    }
}

if (!function_exists('esc'))
{
    function esc($value)
    {
        return htmlspecialchars($value);
    }
}

if (!function_exists('startSession'))
{
    function startSession()
    {
        ini_set('session.use_only_cookies', 1);

        if(!session_id())
        {
            session_start();
        }
    }
}

if (!function_exists('app'))
{
    function app()
    {
        return Tahir\Base\Application::getInstance();
    }
}

if (!function_exists('assets'))
{
    function assets($path)
    {
       return app()->url->link('public/assets/' . $path);
    }
}

if (!function_exists('storage'))
{
    function storage($path)
    {
       echo app()->url->link('public/storage/' . $path);
    }
}
if (!function_exists('storage_r'))
{
    function storage_r($path)
    {
       return app()->url->link('public/storage/' . $path);
    }
}

if (!function_exists('layouts_parts'))
{
    function layouts_parts($path)
    {
        return app()->file->to('layouts/parts/' . $path . '.php');
    }
}

if (!function_exists('hasMessages'))
{
    function hasMessages($key)
    {
        return app()->session->flashHas($key);
    }
}

if (!function_exists('messages'))
{
    function messages($key)
    {
        echo app()->session->getFlash($key);

        $page = app()->request->currentUrl();

        header("Refresh: 2; url=$page");
    }
}

if (!function_exists('errors'))
{
    function errors($key = null)
    {
        $errors = app()->session->getFlash('errors');

        if($key == null) return $errors;

        if(isset($errors[$key]) && count($errors[$key]) > 0 ) return $errors[$key];

        return false;
    }
}

if (!function_exists('hasErrors'))
{
    function hasErrors()
    {    
        return app()->session->flashHas('errors');
    }
}

if (!function_exists('errorField')) {
    function errorField($key)
    {
        if(errors($key))
        {
            $stringError = '';

            foreach(errors($key) as $error)
            {
                $stringError .= $error . '</br>';
            }
            
            return $stringError;
        }
    }
}

if (!function_exists('__r'))
{
    function __r($key)
    {
        return app()->lang->getFromLang($key);
    }
}

if (!function_exists('__'))
{
    function __($key)
    {
        echo __r($key);
    }
}

if (!function_exists('auth'))
{
    function auth()
    {
        if(app()->session->has('auth'))
        {
            $auth = app()->hash->decode(app()->session->get('auth'));
            $user = $auth['auth_user'];

            if(isset($user) && !empty($user))
            {
                return $user;
            }  
        }
        return false;
    }
}
