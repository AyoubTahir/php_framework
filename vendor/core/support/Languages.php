<?php

namespace Tahir\Support;

class Languages
{

    private $app;
    private $langs = [];
    private static $instance;

    public function __construct($app)
    {
        $this->app = $app;

        if(!$this->isLang())
        {
          $this->grapLangsFiles();
        }
    }

    public function isLang()
    {
        return static::$instance instanceof Languages;
    }

    public function getAllLanguages()
    {
        return $this->langs;
    }

    public function getFromLang($key)
    { 
        return $this->langs[$key][$_SESSION['lang']];
    }

    public function grapLangsFiles()
    {
        $langFiles = $this->app->scan->scanDir('app','languages')->filesWithPath();

        foreach($langFiles as $langFile )
        { 
            $this->langs = array_merge($this->langs,require $langFile);
        }
    }

}