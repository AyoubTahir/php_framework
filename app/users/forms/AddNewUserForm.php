<?php

namespace App\Users\Forms;


class AddNewUserForm
{

    public static function fields() : array
    {
        return [
            [
                'tag'           => 'input',
                'type'          => 'text',
                'id'            => 'lastname',
                'class'         => 'form-control',
                'name'          => 'lastname',
                'placeholder'   => 'Last Name',
                '_label'         => ['Last name','form-label'],
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],
            [
                'tag'           => 'input',
                'type'          => 'file',
                'id'            => 'image',
                'class'         => 'form-control',
                'name'          => 'image',
                '_label'         => ['Image','form-label'],
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],

        ];
    }

    public static function form() : array
    {
        return
        [
            'id'           => '',
            'class'          => '',
            'action'            => '/TahirSystem/store/user',
            'method'         => 'post',
            'enctype'          => 'multipart/form-data',
        ];
    }

    public static function button() : array
    {
        return
        [
            'id'           => '',
            'class'        => 'btn btn-primary',
            'type'         => 'submit',
            'onclick'      => 'post',
            'text'         => 'Add User'
        ];
    }

}