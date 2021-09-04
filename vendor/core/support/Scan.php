<?php

namespace Tahir\Support;

class Scan
{

    private $app;
    private $files = [];
    private $filesWithPath = [];
    private $filesWithPathKeyValue = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function scanDir($mainFolder,$spFolder)
    {
        $fullPath = $mainFolder. DS . $spFolder;

        if($this->is_dir($fullPath))
        {
            $this->files = array_merge($this->files,$this->scan($fullPath));
            $this->filesWithPath = array_merge($this->filesWithPath,$this->scanWithFullPath($fullPath));
            $this->filesWithPathKeyValue = array_merge($this->filesWithPathKeyValue,array_combine($this->scan($fullPath),$this->scanWithFullPath($fullPath)));
        }
        else
        {
            foreach($this->scan($mainFolder) as $folderOrFile )
            {
                $fullPath = $mainFolder. DS .$folderOrFile;

                $this->scanDir($fullPath,$spFolder);
            }
        }

        return $this;
    }

    public function files()
    {
        return $this->files;
    }

    public function filesWithPath()
    {
        return $this->filesWithPath;
    }

    public function filesWithPathKeyValue()
    {
        return $this->filesWithPathKeyValue;
    }

    public function is_dir($path)
    {
        return is_dir($this->app->file->to(DS . $path));
    }

    public function scan($mainFolder)
    {
        if($this->is_dir($mainFolder))
        {
           $foldersOrFiles = scandir( $this->app->file->to(DS . $mainFolder) );

            array_shift( $foldersOrFiles );
            array_shift( $foldersOrFiles );

            return $foldersOrFiles; 
        } 

        return [];
    }

    public function scanWithFullPath($mainFolder)
    {
        $foldersOrFiles = $this->scan($mainFolder);

        $path = $this->app->file->to($mainFolder);

        array_walk($foldersOrFiles, function(&$value, $key)use($path) { $value = $path .DS . $value; } );

        return $foldersOrFiles;
    }

    public function fullPath($mainFolder,$folderOrFile,$spFolder)
    {
        return $mainFolder.DS.$folderOrFile.DS.$spFolder;
    }

}