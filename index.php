<?php

ini_set("log_errors", 1);
ini_set("error_log", "./error.log");
error_reporting(E_ALL);

mb_internal_encoding('UTF-8');

require_once 'vendor/autoload.php';
require_once 'model/fwModel.php';
require_once 'model/userModel.php';
require_once 'config/globals.php';
require_once 'config/db.php';

if(empty($_GET['uri'])) $_GET['uri'] = 'index';

$pages = [
    'index',
    'search',
    'product',
    'cart',
    'admin',
    'blog',
    'personal_room',
    'contacts',
    'remote',
    'delivery',
    'callback',
    'search',
    'pick',
    'new',
    'privacy'
];

define('URI', $_GET['uri']);

if(array_search(URI, $pages) !== FALSE) {
    if(URI == 'remote') require_once 'controller/remoteController.php';
    else require_once 'controller/viewController.php';
}
