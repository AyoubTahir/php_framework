<?php
session_start();

defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$composer = ROOT_PATH. DS . 'vendor'. DS .'autoload.php';

if (is_file($composer))
{
    require $composer;
}

use Dotenv\Dotenv;
use Tahir\Base\Application;
use Tahir\File\File;

$dotenv = Dotenv::createImmutable(ROOT_PATH.DS);
$dotenv->load();

$file   = new File(ROOT_PATH);

$app    = Application::getInstance($file);

if(!empty($_SERVER['REQUEST_URI']))
{
  //if (session_status() === PHP_SESSION_NONE)
  //{
    //session_start();
  //}
  $app->run();
}
else
{
  app()->runMigration();  
}

?>

