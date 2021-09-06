<?php

namespace App\Base\Middlewares;

use Tahir\Middlewares\MiddlewareInterface;

class GuestMiddleware implements MiddlewareInterface
{

    public function handle($app, $next)
    {
        if(!auth())
        {
            return $next;
        }

        return $app->url->redirectTo('/users');
    }
}