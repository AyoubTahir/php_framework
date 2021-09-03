<?php
return [


///post/:text/:id/:iid
    'Users/UsersController'         =>
    [
        '/users'                    =>  ['index','get'],
        '/add/user'                 =>  ['create','get'],
        '/store/user'               =>  ['store','post'],
        '/delete/user/:id'          =>  ['delete','get'],
    ],

    'Employees/EmployeesController' =>
    [
        '/employees'  =>  ['index','get'],
    ]


];