<?php

namespace App\Employees\Migrations;

use Tahir\Migration\Migrate;

class MG000001employees extends Migrate
{

    public function up()
    {
        $this->table('employees');
        $this->id();
        $this->varchar('code');
        $this->varchar('address');
        $this->varchar('date');
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