<?php

require_once 'model/jsonModel.php';

$permitted_actions = [
	'User::saveLogged',
	'User::verifyEmail',
	'User::registrate',
	'Order::add',
	'Callback::add',
	'Search::find',
	'Product::getFullPriceCookie',
	// --
	'Order::getUnaccepted',
	'Order::check',
	'Order::accept',
	'Product::get',
	'Product::update',
	'Product::upload',
	'Input::excelUpload',
	'Category::getCategories',
	'Category::getParams',
	'Category::getValues',
	'Category::getFullCategory',
	'Category::addValues',
	'Category::addParams',
	'Category::newCategory',
	'Article::upload'
];

$logged_actions = [

];

$admin_actions = [

];

$logged_actions = array_merge($logged_actions, $admin_actions);
$permitted_actions = array_merge($permitted_actions, $logged_actions);

if(empty($_FILES))
	$_DATA = array_merge($_GET, $_POST);
else
	$_DATA = array_merge($_GET, $_POST, ['files' => array_values($_FILES)]);

define('MODEL', $_DATA['model']);
define('METHOD', $_DATA['method']);
define('ACTION', ucfirst(MODEL)."::".METHOD);

$_DATA = array_diff_key($_DATA, [
	'model' => NULL,
	'method' => NULL,
	'uri' => NULL
]);

if(array_search(ACTION, $permitted_actions)) {
	if(array_search(ACTION, $logged_actions)) {
		if(User::login($_COOKIE['login'], $_COOKIE['pass'])) {

			# ...

		} else {
			JSON::write('permission', 'You are not logged.');
		}
	}

	if(JSON::ok()) {
		try {

			require_once 'model/' . MODEL . 'Model.php';
			JSON::insert(
				'output',
				call_user_func_array(ACTION, array_values($_DATA))
			);

		} catch (InvalidArgumentException $e) {
			JSON::write('error', $e->getMessage());
		}
	}
} else {
	JSON::write('permission', 'Something wrong, maybe update the page.');
}

JSON::pop(TRUE);
