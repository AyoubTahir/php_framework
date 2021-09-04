<?php

namespace Tahir\Database;

use PDO;
use PDOException;

class Database
{
    private $app;
    private static $connection;
    private $table;
    private array $data = [];
    private array $bindings = [];
    private array $wheres = [];
    private array $selects = [];
    private array $joins = [];
    private array $likes = [];
    private $limit;
    private $offset;
    private $rows = 0;
    private array $orderBy = [];
    private $lastId;
    private $countQuery;
    private $count=0;

    public function __construct($app)
    {
        $this->app = $app;

        if(!$this->isConnected())
        {
            $this->connect();
        }
    }

    public function isConnected()
    {
        return static::$connection instanceof PDO;
    }

    public function connect()
    {
        try
        {
            $credentials = $this->app->file->call('config/database');

            static::$connection = new PDO(
                    $credentials['dsn'],
                    $credentials['username'],
                    $credentials['password'],
                    $this->pdoParams()
            );

        }
        catch(PDOException $expection)
        {
            throw new \Exception($expection->getMessage(), (int)$expection->getCode());
        }
    }

    private function pdoParams()
    {
        return [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];
    }

    private function connection()
    {
        return static::$connection;
    }

    public function exec($query)
    {
        $this->connection()->exec($query);
    }

    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    public function from($table)
    {
        return $this->table($table);
    }

    public function data($key, $value = null)
    {
        if(is_array($key))
        {
            $this->data = array_merge($this->data, $key);

            $this->bindParams($key);
        }
        else
        {
            $this->data[$key] = $value;

            $this->bindParams($value);
        }
        
        return $this;
    }

    public function rawQuery(...$args)
    {
        $query = array_shift($args);

        $bindings = $args;

        if(count($bindings) == 1 && is_array($bindings[0]))
        {
            $bindings = $bindings[0];
        }

        try
        {
            $stm = $this->connection()->prepare($query);

            foreach($bindings as $index => $value)
            {
                $stm->bindValue($index + 1, $value);
            }

            $stm->execute();

            return $stm;
        }
        catch(PDOException $expection)
        {
            dd($query,$bindings);
            throw new \Exception($expection->getMessage(), (int)$expection->getCode());
        }

        
    }

    public function setQueryFields()
    {
        $query = '';

        foreach( $this->data as $key => $value)
        {
            $query .= "`{$key}` = ? , ";
        }

        $query = rtrim($query, ', ');

        return $query;
    }

    public function rawSelect($select = null)
    {
        if(! is_null($select))
        {
          $this->selects[] = $select;  
        }

        return $this;
    }

    public function rawJoin($join)
    {
        $this->joins[] = $join;

        return $this;
    }

    public function rawLimit($limit,$offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    public function rawOrderBy($orderBy, $sort = 'ASC')
    {
        $this->orderBy = [$orderBy, $sort];

        return $this;
    }

    public function fetch($table = null)
    {
        if($table)
        {
            $this->table($table);
        }
        
        $query = $this->fetchStatement();

        $result = $this->rawQuery($query, $this->bindings)->fetch();

        $this->reset();
        
        return $result;
    }

    public function fetchAll($table = null)
    {
        if($table)
        {
            $this->table($table);
        }
        
        $query = $this->fetchStatement();

        $stm = $this->rawQuery($query, $this->bindings);

        $results = $stm->fetchAll();

        $this->rows = $stm->rowCount();

        $this->reset();
        
        return $results;
    }

    public function fetchStatement($table = null)
    {
        $query = 'SELECT ';
        $this->countQuery = "SELECT COUNT(*) FROM {$this->table}";
        
        if(count($this->selects) > 0)
        {
            $query .= implode(',', $this->selects);
        }
        else
        {
            $query .= '*';
        }

        $query .= " FROM {$this->table}";

        if($this->joins)
        {
            $imp = implode(' ', $this->joins);

            $query              .= $imp;
            $this->countQuery   .= $imp;
        }

        if($this->wheres)
        {
            $imp = " WHERE " . implode('',$this->wheres);

            $query              .= $imp;
            $this->countQuery   .= $imp;
        }

        if($this->likes && $this->wheres)
        {
            $imp = " AND " . implode('OR ',$this->likes);

            $query              .= $imp;
            $this->countQuery   .= $imp;
        }
        elseif($this->likes && !$this->wheres)
        {
            $imp = " WHERE " . implode('OR ',$this->likes);

            $query              .= $imp;
            $this->countQuery   .= $imp;
        }

        if($this->limit)
        {
            $query .= " LIMIT " . $this->limit;
        }

        if($this->offset)
        {
            $query .= " OFFSET " . $this->offset;
        }

        if($this->orderBy)
        {
            $query .= " ORDER BY " . implode(' ',$this->orderBy);
        }

        return $query; 
    }

    public function rawInsert($table = null)
    {
        if($table)
        {
            $this->table($table);
        }

        $query = "INSERT INTO {$this->table} SET ";

        $query .= $this->setQueryFields();

        $this->rawQuery($query, $this->bindings);

        $this->lastId = $this->connection()->lastInsertId();

        $this->reset();
        
        return $this;
    }

    public function rawUpdate($table = null)
    {
        if($table)
        {
            $this->table($table);
        }

        $query = "UPDATE {$this->table} SET ";

        $query .= $this->setQueryFields();

        if($this->wheres)
        {
            $query .= " WHERE " . implode('',$this->wheres);
        }
        
        $this->rawQuery($query, $this->bindings);

        $this->reset();
        
        return $this;
    }

    public function rawDelete($table = null)
    {
        if($table)
        {
            $this->table($table);
        }

        $query = "DELETE FROM {$this->table} ";

        if($this->wheres)
        {
            $query .= " WHERE " . implode('',$this->wheres);
        }
        
        $this->rawQuery($query, $this->bindings);

        $this->reset();
        
        return $this;
    }

    public function rawWhere($param, $operator = '=', $value)
    {
        $this->wheres[] = $param . $operator . "? ";

        $this->bindParams($value);

        return $this;
    }

    public function rawAndWhere($param, $operator = '=', $value=null)
    {
        if(is_array($param))
        {
            $and = '';
            foreach( $param as $key => $value )
            {
                $this->wheres[] = $and . $key . $operator . "? ";

                $this->bindParams($value);

                $and = 'AND ';
            }
        }
        else
        {
           $this->wheres[] = "AND " . $param . $operator . "? ";
           
           $this->bindParams($value); 
        }
        
        return $this;

    }

    public function rawOrWhere($param, $operator = '=', $value)
    {
        $this->wheres[] = "OR " . $param . $operator . "? ";

        $this->bindParams($value);

        return $this;

    }

    public function rawLike($params, $value=null)
    {
        if(is_array($params))
        {
            foreach($params as $param => $value)
            {
                $this->likes[] = $param . " LIKE " . "? ";

                $this->bindParams('%'.$value.'%');
            }
        }
        else
        {
            $this->likes[] = $params . " LIKE " . "? ";

            $this->bindParams('%'.$value.'%');
        }

        return $this;

    }

    private function bindParams($values)
    {
        if(is_array($values))
        {
            $this->bindings = array_merge($this->bindings, array_values($values));
        }
        else
        {
            $this->bindings[] = esc($values);
        }
        
    }

    public function lastId()
    {
        return $this->lastId;
    }

    public function count()
    {
        return $this->rows;
    }

    public function QueryCount()
    {
        $this->fetchStatement();
        return $this->count = $this->rawQuery($this->countQuery, $this->bindings)->fetchAll(PDO::FETCH_COLUMN)[0];
    }

    public function reset()
    {
        $this->table      =null;
        $this->data       = [];
        $this->bindings   = [];
        $this->wheres     = [];
        $this->selects    = [];
        $this->joins      = [];
        $this->orderBy    = [];
        $this->limit      =null;
        $this->offset     =null;
    }

}