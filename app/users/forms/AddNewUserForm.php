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
                'id'            => '',
                'class'         => '',
                'name'          => 'firstname',
                'placeholder'   => 'First Name',
                '_label'         => 'FirstName',
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],
            [
                'tag'           => 'select',
                'id'            => '',
                'class'         => '',
                'name'          => 'lastname',
                '_label'         => 'LastName',
                'options'       => [10,20,30],
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],
            [
                'tag'           => 'textarea',
                'id'            => '',
                'class'         => '',
                'name'          => 'text',
                'placeholder'   => 'text text',
                '_label'         => 'Text',
                '_before'        => '<div class="mb-3">',
                '_after'         => '</div>',
                '_visible'       => true,
                '_formatter'     => ''
            ],

        ];
    }

}