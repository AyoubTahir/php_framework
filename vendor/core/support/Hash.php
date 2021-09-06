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
        $salt = env('SALT','$2a$07$yeXDSwRp12YopOhV0TrrRw$');
        return crypt($value,$salt);
    }

    public function encode($data)
    {
        $key = env('SALT','$2a$07$yeXDSwRp12YopOhV0TrrRw$');

        if(!is_string($data))
        {
            $data = json_encode((array)$data);
        }

        $enc_key    = base64_decode($key);
        $iv         = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted  = openssl_encrypt($data,'aes-256-cbc',$enc_key,0,$iv);
        
        return base64_encode($encrypted.'::'.$iv);
    }

    public function decode($data)
    {
        $key = env('SALT','$2a$07$yeXDSwRp12YopOhV0TrrRw$');
        $enc_key    = base64_decode($key);

        list($encrypted_data,$iv) = array_pad(explode('::',base64_decode($data),2),2,null);

        $data = openssl_decrypt($encrypted_data,'aes-256-cbc',$enc_key,0,$iv);

        if($this->isJson($data))
        {
            return (array)json_decode($data);
        }

        return $data;
    }

    public function isJson($string)
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

}