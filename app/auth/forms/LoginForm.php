<?php

namespace App\Auth\Forms;


class LoginForm
{

    public static function fields() : array
    {
        return [
            [
                'tag'           => 'input',
                'type'          => 'email',
                'id'            => 'email',
                'class'         => 'form-control',
                'name'          => 'email',
                'placeholder'   => 'Email',
                '_label'         => ['Email','form-label'],
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],
            [
                'tag'           => 'input',
                'type'          => 'password',
                'id'            => 'password',
                'class'         => 'form-control',
                'name'          => 'password',
                'placeholder'   => 'Password',
                '_label'         => ['Password','form-label'],
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],
            [
                'tag'           => 'input',
                'type'          => 'checkbox',
                'id'            => 'remeber',
                'class'         => 'form-check-input',
                'name'          => 'remeber',
                '_label'         => ['Remeber Me','form-check-label'],
                '_before'        => '<div class="mb-3 form-check">',
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
            'action'            => '/TahirSystem/submit',
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
            'onclick'      => '',
            'text'         => 'Login'
        ];
    }

}