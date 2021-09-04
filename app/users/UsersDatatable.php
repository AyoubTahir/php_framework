<?php

namespace App\Users;


class UsersDatatable
{

    public static function columns() : array
    {
        return [
            [
                'db_field'      => 'id',
                'dt_title'      => 'ID',
                'class'         => '',
                'visible'       => true,
                'sortable'      => true,
                'searchable'    => true,
                'formatter'     => ''
            ],
            [
                'db_field'      => 'image',
                'dt_title'      => 'Image',
                'class'         => '',
                'visible'       => true,
                'sortable'      => false,
                'searchable'    => false,
                'formatter'     => function($row){
                    return '<img src="'.storage_r('public/images/'.$row['image']).'" class="rounded-circle" style="width: 50px;" alt="...">';
                }
            ],
            [
                'db_field'      => 'lastname',
                'dt_title'      => 'Lastname',
                'class'         => '',
                'visible'       => true,
                'sortable'      => true,
                'searchable'    => true,
                'formatter'     => ''
            ],
            [
                'db_field'      => 'created_at',
                'dt_title'      => 'Created',
                'class'         => '',
                'visible'       => true,
                'sortable'      => false,
                'searchable'    => false,
                'formatter'     => ''
            ],
            [
                'db_field'      => 'modified_at',
                'dt_title'      => 'Modified',
                'class'         => '',
                'visible'       => true,
                'sortable'      => false,
                'searchable'    => false,
                'formatter'     => ''
            ],
            [
                'db_field'      => '',
                'dt_title'      => 'Action',
                'class'         => '',
                'visible'       => true,
                'sortable'      => false,
                'searchable'    => false,
                'formatter'     => function($row){
                    $actions = '<a class="btn btn-primary" href="/TahirSystem/edit/user/'.$row['id'].'">edit</a>';
                    $actions .= '<a class="btn btn-danger" href="/TahirSystem/delete/user/'.$row['id'].'">delete</a>';

                    return $actions;
                }
            ],
        ];
    }

    public static function tableAttribute() : array
    {
        return
        [
            'status'            => '',
            'table_class'       => ['table'],
            'table_id'          => 'datatable',
            'show_table_thead'  => true,
            'show_table_tfoot'  => false,
            'before'            => '<div>',
            'after'             => '</div>'
        ];
    }

    public static function tablePagination() : array
    {
        return
        [
            'ul_id'             => '',
            'ul_class'          => 'pagination',
            'li_class'          => 'page-item',
            'a_class'           => 'page-link',
        ];
    }

    public static function tableSearch() : array
    {
        return
        [
            'form_class'            => 'd-flex',
            'input_class'           => 'form-control me-2',
            'button'                => true,
            'button_class'          => 'btn btn-outline-success',
        ];
    }

    public static function tablePerPage() : array
    {
        return
        [
            'form_class'            => 'd-flex',
            'select_class'           => 'form-select',
            'perPage'                => ['10','20','50','100'],
        ];
    }

}