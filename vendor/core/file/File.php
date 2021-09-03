<?php

namespace Tahir\File;

class File
{

    private $root;

    public function __construct($root)
    {
        $this->root = $root;
    }

    public function exists($file)
    {
        return file_exists($this->to($file));
    }

    public function call($file, $ext = '.php')
    {
        if (str_contains($file, '.php'))
        {
            return require $this->to($file);
        }
        return require $this->to($file . $ext);
    }

    public function to($path)
    {
        return $this->root . DS . str_replace(['/','\\'] , DS, $path);
    }

    public function callFromVendor($file, $ext = '.php')
    {
        require $this->toVendor($file . $ext);
    }

    public function toVendor($path)
    {
        return $this->to('vendor/core/' . $path);
    }

    public function toPublic($path)
    {
        return $this->to('public/' . $path);
    }

    public function toStorage($path)
    {
        return $this->toPublic('storage/' . $path);
    }
}