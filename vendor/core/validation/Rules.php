<?php

namespace Tahir\Validation;

class Rules
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function required($field,$data)
    {
        return empty($data[$field]) ? "The {$field} is required" : '';
    }

    public function max($field,$data,$max)
    {
        return strlen($data[$field]) > $max ? "The {$field} mast be less then {$max}" : '';
    }

    public function min($field,$data,$min)
    {
        return strlen($data[$field]) < $min ? "The {$field} mast be more then {$min}" : '';
    }

    public function unique($field, $data, $table)
    {
        $result = $this->app->db->rawSelect()->rawWhere($field, '=', $data[$field])->fetch($table);

        return $result ? "The {$field} already exist" : '';
    }

    public function email($field,$data)
    {
        $match = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $data[$field]);
        return !$match ? "This {$field} address not a valid email" : '';
    }

    public function confirmed($field,$data,$confirmedWith)
    {
        return $data[$field] != $data[$confirmedWith] ? "The {$field} does not match {$confirmedWith}" : '';
    }
}