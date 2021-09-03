<?php

defined('MROOT_PATH') or define('MROOT_PATH', dirname(__FILE__));

defined('DS') or define('DS', DIRECTORY_SEPARATOR);


$index = MROOT_PATH . DS . 'public'. DS .'index.php';

if (is_file($index))
{
    require $index;
}

