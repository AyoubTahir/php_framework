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
}
