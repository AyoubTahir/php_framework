<?php

namespace Tahir\File;

class Storage
{

    private $app;
    private $file = [];
    private $fullFileName;
    private $fileName;
    private $ext;
    private $type;
    private $size;
    private $path;
    private $error;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function file($input)
    {
        if(empty($_FILES[$input]))
        {
            return;
        }

        $file = $_FILES[$input];

        $this->error = $file['error'];

        if(!$this->error == UPLOAD_ERR_OK)
        {
            return;
        }

        $this->file = $file;
        
        $this->fullFileName = $this->file['name'];

        $this->fileName     = pathinfo($this->fullFileName)['filename'];
        $this->ext          = pathinfo($this->fullFileName)['extension'];

        $this->type         = $this->file['type'];
        $this->path         = $this->file['tmp_name'];
        $this->size         = $this->file['size'];

    
        return $this;
    }

    public function exists($input)
    {
        return !empty($this->file);
    }

    public function fileName()
    {
        return $this->fileName;
    }

    public function extension()
    {
        return strtolower($this->ext);
    }

    public function type()
    {
        return $this->type;
    }

    public function isImage()
    {
        $extension = ['png','jpg','jpeg','gif','webp'];
        return strpos($this->type,'image/')===0 && in_array($this->ext,$extension);
    }

    public function storage($path, $fileName = null)
    {
        $newFileName = $fileName ?? sha1(mt_rand()) . time();

        $newFileName .= '.' . $this->extension();

        $targetPath = $this->app->file->toStorage($path);

        if(!is_dir($targetPath))
        {
            mkdir($targetPath, 0777,true);
        }

        $uploadedPath = $targetPath . DS . $newFileName;

        move_uploaded_file($this->path,$uploadedPath);
        
        return $newFileName;
    }


}