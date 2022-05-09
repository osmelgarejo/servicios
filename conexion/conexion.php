<?php
include_once("librerias/Medoo.php");

use Medoo\Medoo;

$db = new Medoo([    
   'database_type' => 'mysql',
   'database_name' => 'servicios',
   'server' => 'localhost',
   'username' => 'root',
   'password' => ''    
        ]);
?>