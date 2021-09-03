<?php

namespace Tahir\View;

use Tahir\View\View;
use ReflectionFunction;

class ViewFactory
{

    private $app;
    private $view;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function render($viewPath, array $data = [], $title = '')
    {
        return  new View($this->app->file, $viewPath, $data, $title);
    }

    public function currentWorkingDir()
    {
        $reflection = new ReflectionFunction('render');

        return basename(dirname($reflection->getFilename()));
    }

}