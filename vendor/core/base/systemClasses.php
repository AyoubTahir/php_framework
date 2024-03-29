<?php
return [
    'router'   => 'Tahir\router\Router',
    'request'   => 'Tahir\http\Request',
    'response'   => 'Tahir\http\Response',
    'session'   => 'Tahir\session\Session',
    'cookie'   => 'Tahir\cookies\Cookie',
    'load'   => 'Tahir\base\Loader',
    //'html'   => 'Tahir\http\Response',
    'view'   => 'Tahir\view\ViewFactory',
    'db'   => 'Tahir\database\Database',
    'restFul'   => 'Tahir\restful\RestResponse',
    'validator'   => 'Tahir\validation\Validator',
    'rules'   => 'Tahir\validation\Rules',
    'url'   => 'Tahir\http\Url',
    'arr'   => 'Tahir\support\Arr',
    'storage'   => 'Tahir\file\Storage',
    'lang'   => 'Tahir\support\Languages',
    'scan'   => 'Tahir\support\Scan',
    'migration'   => 'Tahir\migration\Migration',
    'datatable'   => 'Tahir\datatable\Datatable',
    'form'   => 'Tahir\form\FormBuilder',
    'hash'   => 'Tahir\support\Hash',
];