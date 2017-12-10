<?php

mb_internal_encoding('UTF-8');

require_once 'config/globals.php';
require_once 'config/db.php';
require_once 'model/mainModel.php';
require_once 'model/userModel.php';

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
    'search2',
    'pick',
    'new'
];

define('URI', $_GET['uri']);

if(Main::lookSame($pages, URI)) {
    if(URI == 'remote') require_once 'controller/remoteController.php';
    else require_once 'controller/viewController.php';
}
