<?php

namespace Tahir\Migration;

class Migration
{

    private $app;


    public function __construct($app)
    {
        $this->app = $app;
    }

    public function applyMigrations()
    {
        $this->createMigrationTable();

        $files = $this->app->scan->scanDir('app','migrations')->filesWithPathKeyValue();//return filename.php => path

        $executedMg = [];
        $executedMg['insert'] = [];
        $executedMg['update'] = [];

        foreach( $files as $fileName => $file )
        {
            $namespace = str_replace(ROOT_PATH,'',$file);
            $namespace = trim(str_replace($fileName,'',$namespace),DS);
            
            $className = pathinfo($fileName,PATHINFO_FILENAME);
            $class = DS . ucfirst($namespace . DS . $className);

            $obj = new $class();

            $query = $obj->up();

            $queryJson = json_encode($query);

            if($this->migrationExist($fileName))
            {
                $this->terminalMessages("Executing Migration $className ...");
                $obj->exec();
                $this->terminalMessages("$className Migration Executed Successfuly "); 
            
                $executedMg['insert'][] = ['migration'=>$fileName,'query'=>$queryJson];
            }
          
            elseif($this->tableNeedUpdate($fileName,$queryJson))
            {
                $queryArr = (array) json_decode($this->currentQuery($fileName));
                $alertQuery = array_diff($query,$queryArr);
                $dropQuery = array_diff($queryArr,$query);

                $this->terminalMessages("updating Migration $className ...");

                if(isset($alertQuery[0]) && $alertQuery[0] != '')
                {
                    $this->app->db->exec("RENAME TABLE {$queryArr[0]} TO {$alertQuery[0]}");
                }

                if(count($alertQuery) > 0)
                {
                    foreach($alertQuery as $key=>$param)
                    {
                        if($key != 0)
                        {
                            $beforeColumn = $this->getColumn($queryArr[$key-1]);
                            $this->app->db->exec("ALTER TABLE {$query[0]} ADD {$param} AFTER {$beforeColumn}");
                        }  
                    }
                }                

                if(count($dropQuery) > 0)
                {
                    foreach($dropQuery as $key=>$param)
                    {
                        if($key != 0)
                        {
                            $toDropColumn = $this->getColumn($param);
                            $this->app->db->exec("ALTER TABLE {$query[0]} DROP COLUMN {$toDropColumn}");
                        }  
                    }
                }
              
                $oldQ = (array) json_decode($this->currentQuery($fileName));

                if($this->isColumnPositionChanged($query,$oldQ))
                {
                    $this->updateColumnsPosition($query);
                }

                $this->terminalMessages("$className Migration updated Successfuly ");

                $executedMg['update'][$fileName] = ['query'=>$queryJson];
            }

        } 


        if(count($executedMg['insert']) > 0)
        {
            $this->addNewMigrations($executedMg['insert']);
        }
        if(count($executedMg['update']) > 0)
        {
            $this->updateMigrationsQuery($executedMg['update']);
        }
        if(count($executedMg['update']) == 0 && count($executedMg['insert']) == 0)
        {
            $this->terminalMessages('migrations already Executed');
        }
    
    }

    public function createMigrationTable()
    {
        $this->app->db->exec('CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` varchar(255) NOT NULL ,
            `query` varchar(255) NOT NULL ,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;');
    }

    public function getExistMigrations()
    {
        return $this->app->db->rawQuery("SELECT migration FROM migrations")->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function migrationExist($param)
    {
        $data = $this->app->db->rawQuery("SELECT migration FROM migrations WHERE migration = ?",$param)->fetchAll(\PDO::FETCH_COLUMN);

        return count($data) > 0 ? false: true;
    }
    public function tableNeedUpdate($migration,$query)
    {
        $data = $this->app->db->rawQuery("SELECT migration FROM migrations WHERE migration = ? AND query = ? ",$migration,$query)->fetchAll(\PDO::FETCH_COLUMN);

        return count($data) > 0 ? false: true;
    }

    public function currentQuery($migration)
    {
        return $this->app->db->rawQuery("SELECT query FROM migrations WHERE migration = ? ",$migration)->fetch(\PDO::FETCH_COLUMN);
    }

    public function addNewMigrations($newMgs)
    {
        foreach( $newMgs as $mg )
        {
            $this->app->db->data($mg)->rawInsert('migrations');
        }
    }

    public function updateMigrationsQuery($Mgs)
    {
        foreach( $Mgs as $key => $mg )
        {
            $this->app->db->data($mg)->rawWhere('migration', '=', $key)->rawUpdate('migrations');
        }
    }

    public function terminalMessages($message)
    {
        $date = date('Y-m-d H:i:s');
        echo "[$date] - $message".PHP_EOL;
    }

    private function getColumn(string $str)
    {
        $pos = strpos($str, '`', 1);
        return substr($str, 0, $pos+1);
    }

    private function isColumnPositionChanged(array $newQuery ,array $oldQuery)
    {
        $result = [];

        foreach($newQuery as $k => $v)
        {
            $result[$v] = array_search($v, $oldQuery);
        }
        return array_values($result) != array_keys($oldQuery);
    }

    private function updateColumnsPosition(array $newQuery)
    {
        $arr = $newQuery;

        foreach($newQuery as $index => $value)
        {
            if( $index == 0 || $index == 1 )
            {
                continue;
            }
            else
            {
                $columnName = $this->getColumn($value);
                $beforColumn = $this->getColumn($arr[$index-1]);
                $this->app->db->exec("ALTER TABLE {$newQuery[0]} CHANGE {$columnName} {$value} AFTER {$beforColumn}".PHP_EOL);
            }
        }
    }

}