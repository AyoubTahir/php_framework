<?php

namespace Tahir\View;

class View implements ViewInterface
{
    private $file;
    private $viewPath;
    private $originalViewPath;
    private $data = [];
    private $output;
    private $title = 'home';

    public function __construct($file, $viewPath, array $data, $title)
    {
        $this->file = $file;

        $this->preparePath($viewPath);

        $this->data = $data;

        $this->title = $title;
    }

    public function preparePath($viewPath)
    {
        $viewPath = 'app' . DS . $viewPath;

        $this->originalViewPath = $viewPath;

        $viewPath =  $viewPath . '.php';

        $this->viewPath = $this->file->to($viewPath);

        if(!$this->viewFileExist($viewPath))
        {
            dd('not exist');
        }
    }

    public function viewFileExist($viewPath)
    {
        return $this->file->exists($viewPath);
    }

    public function replaceWithCurentView($content)
    {
        ob_start();

        extract($this->data);

        include_once $this->file->to($this->originalViewPath.'.php');

        $viewContent = ob_get_clean();
        
        return str_replace('@content',$viewContent,$content);
    }

    public function replaceWithCurentTitle($content)
    {
        return preg_replace('#\{{(.+?)}}#i', $this->title , $content);
    }

    public function getOutput()
    {
        if(is_null($this->output))
        {
            ob_start();

            require($this->file->to('layouts/main.php'));

            $content = ob_get_clean();

            $content = $this->replaceWithCurentView($content);

            $content = $this->injectAssets($content);

            $this->output = $this->replaceWithCurentTitle($content);

        }

        return $this->output;   

    }

    public function __toString()
    {
        return $this->getOutput();
    }

    public function injectAssets($content)
    {
        $assetsTypes = $this->file->call('config/assets');

        $pagePath = $this->originalViewPath;

        $styles = '';
        $scripts = '';

        foreach($assetsTypes as $type => $assets)
        {
            foreach($assets as $path => $pages)
            {
                if( $type == 'styles' )
                {
                    if(( is_array($pages) && in_array($pagePath,$pages)) || $pages == 'all')
                    $styles .= '<link rel="stylesheet" href="'. $path .'">';
                }
                elseif( $type == 'scripts' )
                {
                    if(( is_array($pages) && in_array($pagePath,$pages)) || $pages == 'all')
                    $scripts .= '<script src="'.$path.'"></script>';
                }

            }
        }
        //dd($styles, $scripts);

        $content = str_replace(['@styles', '@scripts'], [$styles, $scripts], $content);

        return $content;
    }

}