<?php

$phone = $_POST['phone'];
$public = $_POST['public'];
$pass = $_POST['pass'];
$confirm = $_POST['confirm'];

if(!empty($phone)) {
	if(!preg_match("/^\+([0-9]{1,3}) \(([0-9]{1,3})\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$/is", $phone))
		$JSON->write("phone", "Неверный формат номера.");
	else {
		if(empty($query))
			$query .= "SET `phone` = '$phone' ";
		else
			$query .= "SET `phone` = '$phone' ";
	}
}
if($JSON->ok() && !empty($public)) {
	if(empty($query))
		$query .= "SET `public` = '$public' ";
	else
		$query .= "AND `public` = '$public' ";

}
if($JSON->ok() && (!empty($pass) || !empty($confirm))) {
	if(strlen($pass) < 8)
		$JSON->write("pass", "Длинна пароля должна быть не меньше 8 символов.");
	elseif(strlen($pass) > 32)
		$JSON->write("pass", "Длинна пароля должна быть не больше 32 символов.");
	elseif(preg_match("/'\{\}\[\]\(\)\`\"/",$pass))
		$JSON->write("pass", "Некоторые символы не допустимы в пароле.");

	if($confirm != $pass)
		$JSON->write("confirm", "Повторите пароль корректно.");
	elseif(empty($confirm))
		$JSON->write("confirm", "Повторите пароль.");

	if($JSON->ok()) {
		if(empty($query))
			$query .= "SET `pass` = '".Engine::hashPass($pass)."' ";
		else
			$query .= "AND `pass` = '".Engine::hashPass($pass)."' ";
	}
}

if($JSON->ok() && !empty($query)) {
	if(!$SQL->query("
		UPDATE `users`
		$query
		LIMIT 1
	")) {
		$JSON->write('system', "Системная проблема, попробуйте позже.");
		Main::fatalError('edituserListener', $JSON->pop());
	}
}
