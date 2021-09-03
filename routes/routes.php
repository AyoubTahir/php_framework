<?php

$app    = Tahir\Base\Application::getInstance($file);

$app->router->add('/','Users/UsersController@index','get');

$app->router->add('/post/:text/:id/:iid','Users/UsersController@blog','get');

$app->router->add('/404','Error/NotFound@index','get');

$app->router->notFound('/404');