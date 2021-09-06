<?php
use App\Users\UsersController;
use App\Auth\AuthController;
use App\Base\Middlewares\AuthMiddleware;
use App\Base\Middlewares\GuestMiddleware;

$app    = Tahir\Base\Application::getInstance($file);

$group = 
[
    'prefix'        => '/',
    'controller'    => UsersController::class,
    'middlewares'   => [AuthMiddleware::class]
];

$app->router->group($group,function($router){

    $router->add('/users','index','get');
    $router->add('/add/user','create','get');

});

$groupAuth = 
[
    'prefix'        => '/',
    'controller'    => AuthController::class,
    'middlewares'   => [GuestMiddleware::class]
];

$app->router->group($groupAuth,function($router){

    $router->add('/login','index','get');
    $router->add('/submit','login','post');
    $router->middlewares([AuthMiddleware::class])->add('/logout','logout','get');

});

//$app->router->middlewares([AuthMiddleware::class])->add('/logout',[AuthController::class,'logout'],'get');

//routes
/*
$app->router->middlewares([AuthMiddleware::class,Test::class])
            ->add('/users',[UsersController::class,'index'],'get');

$app->router->middlewares([AuthMiddleware::class])
            ->add('/add/user',[UsersController::class,'create'],'get');

$app->router->add('/store/user',[UsersController::class,'store'],'post');
$app->router->add('/delete/user/:id',[UsersController::class,'edit'],'get');

$app->router->add('/login',[AuthController::class,'index'],'get');
$app->router->add('/submit',[AuthController::class,'login'],'post');

//$app->router->add('/employees','Employees/EmployeesController@index','get');

//$app->router->add('/404','Error/NotFound@index','get');
//$app->router->notFound('/404');*/