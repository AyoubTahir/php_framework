<?php

namespace Tahir\Base;

abstract class Model
{

    protected $app;

    protected string $table;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function __get($key)
    {
        return $this->app->get($key);
    }

    public function __call($method,$args)
    {
        return call_user_func_array([$this->app->db,$method],$args);
    }

    public function create($data)
    {
        $this->db->data($data)->rawInsert($this->table);
    }

    public function update($data,$id)
    {
        if(count($this->find($id)) > 0)
        {
          $this->db->data($data)->rawWhere($this->idName, '=', $id)->rawUpdate($this->table); 
          
          return $this;
        }
        
        return false;
    }

    public function delete($id)
    {
        if(is_array($id) && count($id) > 0)
        {
            foreach( $id as $val )
            {
                if(count($this->find($val)) > 0)
                {
                    $this->db->rawWhere($this->idName, '=', $val)->rawDelete($this->table); 
                }
            }

            return $this;
        }
        elseif(!is_array($id) && !empty($id))
        {
            if($this->find($id) != [])
            {
                $this->db->rawWhere($this->idName, '=', $id)->rawDelete($this->table); 
                
                return $this;
            }
        }
        
        return false;
    }

    public function all()
    {
        return $this->fetchAll($this->table);
    }

    public function find($id)
    {
        $result =  $this->rawWhere($this->idName, '=', $id)->fetch($this->table);

        if($result)
        {
            return $result;
        }

        return [];
    }

    public function findOrFail($id)
    {
        $result =  $this->rawWhere($this->idName, '=', $id)->fetch($this->table);

        if($result)
        {
            return $result;
        }

        $output = $this->app->view->render('vendor/core/errors/views/404',[]);
        
        $this->app->response->setOutput($output);

        $this->app->response->send();

        exit;
    }

    public function Select($select = null)
    {
        $this->rawSelect($select);
        return $this;
    }

    public function where($id,$operator = '=',$value)
    {
        $this->rawWhere($id, $operator, $value);
        return $this;
    }

    public function andWhere($id,$operator = '=',$value)
    {
        $this->rawAndWhere($id, $operator, $value);
        return $this;
    }

    public function orWhere($id,$operator = '=',$value)
    {
        $this->rawOrWhere($id, $operator, $value);
        return $this;
    }

    public function search($param,$value=null)
    {
        $this->rawLike($param, $value);

        return $this;
    }

    public function paginate($limit,$offset = 0)
    {
        $this->rawLimit($limit, $offset);
        
        return $this;
    }

    public function orderBy($id,$sort)
    {
        $this->rawOrderBy($id,$sort);
        return $this;
    }

    public function count()
    {
        return $this->from($this->table)->QueryCount();
    }

    public function get()
    {
        return $this->fetchAll($this->table);
    }

}