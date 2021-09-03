<?php

return
[
    'styles' =>
    [
        //'/assets/style.css'           => ['users/views/create','users/views/edit'],
        //'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css'   => 'all',
        assets('css/style.css')       => 'all',
        assets('css/bootstrap.css')       => 'all',
    ],

    'scripts' =>
    [
        //assets('js/main.js ')        => ['users/views/edit'],
        assets('js/main.js ')          => 'all',
    ]
];