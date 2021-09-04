<?php

namespace Tahir\Form;

use Tahir\Form\AbstractFormBuilder;

class FormBuilder extends AbstractFormBuilder
{

    private $app;
    protected string $element = '';
    private $fields = [];
    private $data = [];


    public function __construct($app)
    {
        $this->app = $app;
        parent::__construct();
    }

    public function create($formClass,$data=[])
    {
        $obj                    = new $formClass();
        $this->fields = $obj->fields();
        $this->form = $obj->form();
        $this->button = $obj->button();
        //$this->setAttr('fields',$obj->fields());
        $this->data = $data;

        return $this;
    }

    public function form()
    {
        $this->element = "<form id='{$this->form['id']}' class='{$this->form['class']}' action='{$this->form['action']}' method='{$this->form['method']}' enctype='{$this->form['enctype']}'>";

        foreach($this->fields as $field)
        {
            $this->element .= $field['_before'];

            $this->element .= $this->field($field);

            $this->element .= isset($field['name']) ? '<div class="form-text text-danger">'.errorField($field['name']).'</div>' : '';

            $this->element .= $field['_after'];
        }

        $this->element .= "<button type='{$this->button['type']}' id='{$this->button['id']}' class='{$this->button['class']}'>{$this->button['text']}</button>";

        $this->element .= "</form>";
        
        return $this->element;
    }

    private function field($field)
    {
        $tag = $this->getTag($field);

        $fieldString = '';

        if (isset($field['_formatter']) && is_callable($field['_formatter']))
        {
            $fieldString .= call_user_func_array($field['_formatter'], $this->data);
        }
        elseif($tag)
        {
            $fieldString .= isset($field['_label']) ? "<label for='{$field['id']}' class='{$field['_label'][1]}'>{$field['_label'][0]}</label>" : '';
            
            $fieldString .= "<{$tag} ";

            $stringOptions = '';

            $field = $this->insertValue($field);//add value if data['name] exist

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

            if(( isset($field['_visible']) && !$field['_visible']))
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

    private function insertValue($field)
    {
        if(isset($field['name']) && isset($this->data[$field['name']]))
        {
            $field['value'] = $this->data[$field['name']];
        }

        return $field;
    }

}