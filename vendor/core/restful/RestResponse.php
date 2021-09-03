<?php

namespace Tahir\Restful;

class RestResponse
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function response($data,$code)
    {
        if(is_array($data))
        {
            if ($code == 200)
            {
                http_response_code(200);
            }
            else
            {
                http_response_code(500);
            }

            echo json_encode($data);

            exit();
        }
    }

}