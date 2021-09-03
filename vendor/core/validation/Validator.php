<?php

namespace Tahir\Validation;

class Validator
{
    private $app;
    protected array $data = [];
    protected array $aliases = [];
    protected array $rules = [];
    public array $errors=[];


    public function __construct($app)
    {
        $this->app = $app;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function validate($rules)
    {
        foreach ($rules as $field => $fieldRules)
        {
            if($this->fieldExist($field))
            {
                $this->extractRules($field,$fieldRules);
            }
        }        

        return $this;
    }

    protected function extractRules($field,$rules)
    {
        if (is_string($rules))
        {
            $rules = (array) (str_contains($rules, '|') ? explode('|', $rules) : $rules);
        }

        foreach( $rules as $rule )
        {
            if (is_string($rule))
            {
                $singleRule = explode(':', $rule);

                $params = explode(',', end($singleRule));
                
                $action = $singleRule[0];

                if(\is_callable([$this->app->rules, $action ]))
                {
                    $errorMessage =$this->app->rules->$action($field,$this->data,...$params);
                    if($errorMessage != '')
                    {
                       $this->errors[$field][] = $errorMessage;
                    }
                }
            }
        }
    }

    protected function fieldExist($field)
    {
        return isset($this->data[$field]);
    }

    public function passes()
    {
        return count($this->errors()) === 0;
    }

    public function errors($key = null)
    {
        return $key ? $this->errors[$key] : $this->errors;
    }
}