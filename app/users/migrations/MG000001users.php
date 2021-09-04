<?php
namespace App\Users\Migrations;

use Tahir\Migration\Migrate;

class MG000001users extends Migrate
{

    public function up()
    {
        $this->table('users');
        $this->id();
        $this->varchar('firstname');
        $this->varchar('lastname');
        $this->varchar('email');
        $this->varchar('password');
        $this->varchar('image');
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