<?php

namespace Tahir\Form;

use Tahir\Form\AbstractFormBuilder;

class FormBuilder extends AbstractFormBuilder
{

    private $app;
    protected string $element = '';
    private $fields = [];


    public function __construct($app)
    {
        $this->app = $app;
        parent::__construct();
    }

    public function create($formClass)
    {
        $obj                    = new $formClass();
        $this->fields = $obj->fields();
        //$this->setAttr('fields',$obj->fields());

        return $this;
    }

    public function form()
    {
        $this->element = "<form id='' class='' action='' method='' enctype=''>";

        foreach($this->fields as $field)
        {
            $this->element .= $field['_before'];

            $this->element .= isset($field['_label']) ? "<label for='{$field['id']}'>{$field['_label']}</label>" : '';

            $this->element .= $this->field($field);

            $this->element .= '<div class="form-text text-danger">'.errorField($field['name']).'</div>';

            $this->element .= $field['_after'];
        }

        $this->element .= "<button type='submit'>submit</button>";

        $this->element .= "</form>";
        
        return $this->element;
    }

    private function field($field)
    {
        $tag = $this->getTag($field);

        $fieldString = '';

        if (isset($field['_formatter']) && is_callable($field['_formatter']))
        {
            $fieldString .= call_user_func_array($field['_formatter'], []);
        }
        elseif($tag)
        {
            $fieldString .= "<{$tag} ";
            $stringOptions = '';

            foreach($field as $key => $attr)
            {
                if(!str_contains($key,'_') && $key != 'tag')
                {
                    if($key == 'options')
                    {
                        foreach($attr as $option)
                        {
                            $stringOptions .= "<option value='$option'>$option</option>";
                        }
                    }
                    else
                    {
                      $fieldString .= "$key='$attr' ";
                    }   
                    
                } 
            }

            $fieldString .= $field['tag'] == 'input' ? '/>' : ">$stringOptions</{$field['tag']}>";

            if(!$field['_visible'])
            {
                $fieldString = '';
            }   
        }

        return $fieldString;
    }

    private function getTag($field)
    {
        return isset($field['tag']) ? $field['tag'] : '';
    }

}