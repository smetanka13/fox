<?php

require_once 'model/jsonModel.php';
require_once 'config/permitted.php';

$logged_actions = array_merge($logged_actions, $admin_actions);
$permitted_actions = array_merge($permitted_actions, $logged_actions);

if(empty($_FILES))
	$_DATA = array_merge($_GET, $_POST);
else
	$_DATA = array_merge($_GET, $_POST, ['files' => array_values($_FILES)]);

define('MODEL', $_DATA['model']);
define('METHOD', $_DATA['method']);
define('ACTION', ucfirst(MODEL)."::".METHOD);

if(isset($_DATA['decoder'])) {
	$_DATA['decoder'] = json_decode($_DATA['decoder'], TRUE);

	foreach ($_DATA['decoder'] as $key => $value) {
		if(!isset($_DATA[$key])) continue;

		switch ($value) {
			case 'JSON':
				$_DATA[$key] = json_decode($_DATA[$key], TRUE);
				break;
			case 'XML':
				# code...
				break;
		}
	}
}

if(isset($_DATA['args_array'])) define('ARGS_ARRAY', TRUE);
else define('ARGS_ARRAY', FALSE);

$_DATA = array_diff_key($_DATA, [
	'model' => NULL,
	'method' => NULL,
	'uri' => NULL,
	'decoder' => NULL
]);

if(ARGS_ARRAY) $_DATA = [$_DATA];

if(array_search(ACTION, $permitted_actions) !== FALSE) {
	if(array_search(ACTION, $logged_actions) !== FALSE) {
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
