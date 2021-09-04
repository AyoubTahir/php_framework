<?php

namespace Tahir\Support;

class Hash
{

    protected $app;

    protected string $table;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function crypt($value)
    {
        $salt = '$2a$07$yeXDSwRp12YopOhV0TrrRw$';
        return crypt($value,$salt);
    }

}