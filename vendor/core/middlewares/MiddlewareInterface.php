<?php

namespace Tahir\Middlewares;

use Tahir\Base\Application;

interface MiddlewareInterface
{
    public function handle(Application $app, $next);
}