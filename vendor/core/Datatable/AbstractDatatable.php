<?php

namespace Tahir\Datatable;

abstract class AbstractDatatable
{
    protected $tableParams = 
    [
        'status'            => '',
        'table_class'       => ['table'],
        'table_id'          => 'datatable',
        'show_table_thead'  => true,
        'show_table_tfoot'  => false,
        'before'            => '<div>',
        'after'             => '</div>'
    ];
    protected $columnParams = 
    [
        'db_field'          => '',
        'dt_title'          => '',
        'class'             => '',
        'visible'           => true,
        'sortable'          => false,
        'searchable'        => false,
        'formatter'         => ''
    ];

    protected $tablePagination = 
    [
        'ul_id'             => '',
        'ul_class'          => 'pagination',
        'li_class'          => 'page-item',
        'a_class'           => 'page-link',
    ];

    protected $tableSearch = 
    [
        'form_class'            => 'd-flex',
        'input_class'           => 'form-control me-2',
        'button'                => true,
        'button_class'          => 'btn btn-outline-success',
    ];

    protected $tablePerPage = 
    [
        'form_class'            => 'd-flex',
        'select_class'           => 'form-select',
        'perPage'                => ['10','20','50','100'],
    ];

    public function __construct()
    {
        /*
        foreach ($this->attr as $key => $value)
        {
            if (!$this->validate($key, $value))
            {
                $this->validate($key, $value);
            }
        }*/
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

    protected function validate(string $key, $value) : void
    {
        if (empty($key))
        {
            throw new BaseInvalidArgumentException('Inavlid or empty attribute key. Ensure the key is present and of the correct data type ' . $value);
        }

        switch ($key)
        {
            case 'status' :
            case 'table_id' :
            case 'before' :
            case 'after' :
                if (!is_string($value))
                {
                    throw new BaseInvalidArgumentException('Invalid argument type ' . $value . ' should be a string');
                }
                break;
            case 'show_table_thead' :
            case 'show_table_tfoot' :
                if (!is_bool($value))
                {
                    throw new BaseInvalidArgumentException('Invalid argument type ' . $value . ' should be a boolean');
                }
                break;
            case 'table_class' :
                if (!is_array($value))
                {
                    throw new BaseInvalidArgumentException('Invalid argument type ' . $value . ' should be a array');
                }
                break;
        }
        $this->attr[$key] = $value;
    }
}
