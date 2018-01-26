<?php

use Medoo\Medoo;

FW::$DB = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'foxmotor_db',
    'server' => 'foxmotor.mysql.tools',
    'username' => 'foxmotor_db',
    'password' => 'Ttgu9yKn',
    'charset' => 'utf8',
    'option' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
]);
