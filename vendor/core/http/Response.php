<?php

namespace Tahir\Http;

class Response
{

    private $app;
    private $headers = [];
    private $content = '';

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function setOutput($content)
    {
        $this->content = $content;
    }

    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    public function send()
    {
        header_remove();
        ob_clean();

        $this->sendHeaders();

        $this->sendOutput();
    }

    public function sendHeaders()
    {
        foreach( $this->headers as $header => $value )
        {
            header($header . ': ' . $value);
        }
    }

    public function sendOutput()
    {
        if(is_array($this->content))
        {
            $this->setHeader('Access-Control-Allow-Origin','*');
            $this->setHeader('Content-type','application/json; charset=utf-8');
            $this->sendHeaders();
            $this->app->restFul->response($this->content,200);
        }

        echo $this->content;
    }



}