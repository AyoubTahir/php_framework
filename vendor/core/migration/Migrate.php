<?php

namespace Tahir\Migration;

class Migrate
{
    private array $columns = [];
    private $table;
    private $query;

    public function execute()
    {
        app()->db->exec($this->query);
    }

    public function create()
    {
        $arr = $this->columns;

        array_shift($arr);

        $params = implode(',',array_values($arr));

        $this->query = "CREATE TABLE IF NOT EXISTS `{$this->table}` ({$params}) ENGINE=INNODB;";
        
        return $this->columns;
    }

    public function table($table)
    {
        $this->table = $table;
        $this->columns[] = $this->table;
    }

    public function id($column = 'id')
    {
        $this->columns[] = "`{$column}` INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function unsignedBigInteger($column,$autoIncrement = false)
    {
        $autoIncrement = $autoIncrement ? ' AUTO_INCREMENT ' : ' ';

        $this->columns[] = "`{$column}` BIGINT{$autoIncrement}PRIMARY KEY";
    }

    public function varchar($column,$default = '',$nullable = false,$size = 255)
    {
        $default    = $default != '' ?  "DEFAULT `{$default}`" : '';
        $nullable   = $nullable      ?  "NULL" : "NOT NULL";

        $this->columns[] = "`{$column}` varchar({$size}) {$nullable} {$default}";
    }

    public function timestamps($column = '',$default = '',$nullable = false)
    {
        $default    = $default != '' ?  "DEFAULT `{$default}`" : '';
        $nullable   = $nullable      ?  "NULL" : "NOT NULL";

        if($column != '')
        {
            $this->columns[] = "`{$column}` TIMESTAMP {$nullable} {$default}";
        }
        else
        {
            $this->columns[] = "`created_at` TIMESTAMP {$nullable} DEFAULT CURRENT_TIMESTAMP";
            $this->columns[] = "`updated_at` TIMESTAMP {$nullable} DEFAULT CURRENT_TIMESTAMP";
        }   
    }


}