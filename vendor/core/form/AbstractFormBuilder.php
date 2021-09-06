<?php

namespace Tahir\Form;

abstract class AbstractFormBuilder
{
    protected $field = 
    [
        'status'            => '',
        'table_class'       => ['table'],
        'table_id'          => 'datatable',
        'show_table_thead'  => true,
        'show_table_tfoot'  => false,
        'before'            => '<div>',
        'after'             => '</div>'
    ];
    protected $form = 
    [
        'id'           => '',
        'class'          => '',
        'action'            => '/TahirSystem/store/user',
        'method'         => 'post',
        'enctype'          => 'multipart/form-data',
    ];
    protected $button = 
    [
        'id'           => '',
        'class'        => '',
        'type'         => 'submit',
        'onclick'      => '',
        'text'         => 'Add User'
    ];

    public function __construct()
    {

    }

    public function setAttr($paramString,$attributes = [])
    {
        if (is_array($attributes) && count($attributes) > 0)
        {
            $propKeys = array_keys($this->$paramString);

            foreach ($attributes as $key => $value)
            {
                if (!in_array($key, $propKeys))
                {
                    throw new BaseInvalidArgumentException('Invalid property key set.');
                }

                $this->$paramString[$key] = $value;
            }
        }
    }

    protected function createToken()
    {
		$seed = $this->urlSafeEncode(random_bytes(8));
		$t = time();
		$hash =  $this->urlSafeEncode(hash_hmac('sha256', session_id() . $seed . $t, env('SALT','$2a$07$yeXDSwRp12YopOhV0TrrRw$'), true));
		return  $this->urlSafeEncode($hash . '|' . $seed . '|' . $t);
	}

    protected function validateToken($token)
    {
		$parts = explode('|',  $this->urlSafeDecode($token));
		if(count($parts) === 3)
        {
			$hash = hash_hmac('sha256', session_id() . $parts[1] . $parts[2], CSRF_TOKEN_SECRET, true);
			
            if(hash_equals($hash,  $this->urlSafeDecode($parts[0])))
            {
				return true;
			}
		}

		return false;
	}

	protected function urlSafeEncode($m)
    {
		return rtrim(strtr(base64_encode($m), '+/', '-_'), '=');
	}
	public function urlSafeDecode($m)
    {
		return base64_decode(strtr($m, '-_', '+/'));
	}
}
