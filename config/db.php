<?php

use Medoo\Medoo;

FW::$DB = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'autoshop',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'Thuglife200Thuglife200Thuglife200',
    'charset' => 'utf8',
    'option' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
]);
