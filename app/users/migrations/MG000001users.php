<?php
namespace App\Users\Migrations;

use Tahir\Migration\Migrate;

class MG000001users extends Migrate
{

    public function up()
    {
        $this->table('user');
        $this->id();
        $this->varchar('firsst_name');
        $this->varchar('for_name');
        $this->timestamps();
        return $this->create();
    }

    public function exec()
    {
        $this->execute();
    }

    public function down()
    {
        echo 'down migration'.PHP_EOL;
    }
    
}