<?php
return [

    [
        'route'         => '/users',
        'action'        => [App\Users\UsersController::class,'index'],
        'method'        => 'get',
        'middelwares'   => [App\Base\Middlewares\AuthMiddleware::class]

    ],
    [
        'route'         => '/add/user',
        'action'        => [App\Users\UsersController::class,'create'],
        'method'        => 'get',
        'middelwares'   => [],
    ],
    [
        'route'         => '/login',
        'action'        => [App\Auth\AuthController::class,'index'],
        'method'        => 'get',
        'middelwares'   => [],
    ],

];