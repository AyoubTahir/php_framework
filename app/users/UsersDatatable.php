<?php

namespace App\Users;


class UsersDatatable
{

    public static function columns() : array
    {
        return [
            [
                'db_field'  => 'id',
                'dt_title'  => 'ID',
                'class'     => '',
                'visible'   => true,
                'sortable'  => true,
                'formatter' => ''
            ],
            [
                'db_field'  => 'image',
                'dt_title'  => 'Image',
                'class'     => '',
                'visible'   => true,
                'sortable'  => false,
                'formatter' => function($row){
                    return '<img src="'.storage_r('public/images/'.$row['image']).'" class="rounded-circle" style="width: 50px;" alt="...">';
                }
            ],
            [
                'db_field'  => 'lastname',
                'dt_title'  => 'Lastname',
                'class'     => '',
                'visible'   => true,
                'sortable'  => true,
                'formatter' => ''
            ],
            [
                'db_field'  => 'created_at',
                'dt_title'  => 'Created',
                'class'     => '',
                'visible'   => true,
                'sortable'  => false,
                'formatter' => ''
            ],
            [
                'db_field'  => 'modified_at',
                'dt_title'  => 'Modified',
                'class'     => '',
                'visible'   => true,
                'sortable'  => false,
                'formatter' => ''
            ],
            [
                'db_field'  => '',
                'dt_title'  => 'Action',
                'class'     => '',
                'visible'   => true,
                'sortable'  => false,
                'formatter' => function($row){
                    $actions = '<a class="btn btn-primary" href="/TahirSystem/edit/user/'.$row['id'].'">edit</a>';
                    $actions .= '<a class="btn btn-danger" href="/TahirSystem/delete/user/'.$row['id'].'">delete</a>';

                    return $actions;
                }
            ],
        ];
    }

}