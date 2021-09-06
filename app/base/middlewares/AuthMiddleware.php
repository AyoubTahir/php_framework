<?php

namespace App\Base\Middlewares;

use Tahir\Middlewares\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{

    public function handle($app, $next)
    {
        if($app->cookie->has('auth') && !auth())
        {
            $app->session->set('auth',$app->cookie->get('auth'));
        }

        if(auth())
        {
            return $next;
        }

        return $app->url->redirectTo('/login');
    }
}