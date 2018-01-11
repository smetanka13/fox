<?php

$email = $_POST['email'];
$pass = $_POST['pass'];
$confirm = $_POST['confirm'];
$public = $_POST['public'];
$phone = $_POST['phone'];

$CheckEmail = $SQL->query("
	SELECT * FROM `users`
	WHERE `email` = '$email'
");
if(!preg_match("/^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})$/is", $email))
	$JSON->write("email", "Неверный формат email адреса.");
if(($CheckEmail -> fetch_assoc()) != FALSE)
	$JSON->write("email", "Этот email адрес уже занят.");

if($phone != NULL && !preg_match("/^\+([0-9]{1,3}) \(([0-9]{1,3})\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$/is", $phone))
	$JSON->write("phone", "Неверный формат номера.");

if(strlen($pass) < 8)
	$JSON->write("pass", "Длинна пароля должна быть не меньше 8 символов.");
if(strlen($pass) > 32)
	$JSON->write("pass", "Длинна пароля должна быть не больше 32 символов.");
if (preg_match("/'\{\}\[\]\(\)\`\"/",$pass))
	$JSON->write("pass", "Некоторые символы не допустимы в пароле.");

if($confirm != $pass)
	$JSON->write("confirm", "Повторите пароль корректно.");
if(empty($confirm))
	$JSON->write("confirm", "Повторите пароль.");

if($JSON->ok()) {
	if($SQL->query("
		INSERT INTO `users` (`email`, `pass`, `phone`, `public`)
		VALUES ('$email', '".Main::hashPass($pass)."', '$phone', '$public')
	")) {
		$key = Main::generateKey();
		if($SQL->query("
			INSERT INTO `confirm` (`email`, `key`)
			VALUES ('$email', '$key')
		")) {
			$subject = "[".NAME."] регистрация.";
			$headers = 'From: '.NAME. "\r\n";
			$message = "Для активации вашего аккаунта пройдите по ссылке: ".URL."/controller/listenerController.php?listener=email&key=".$key;
			if(!mail($email, $subject, $message, $headers)) {
				$SQL->query("
					DELETE FROM `users`
					WHERE `email` = '$email';
				");
				$SQL->query("
					DELETE FROM `confirm`
					WHERE `email` = '$email';
				");
				$JSON->write("system", "Системная проблема, попробуйте позже (#1).");
			}
		} else {
			$SQL->query("
				DELETE FROM `users`
				WHERE `email` = '$email';
			");
			$JSON->write("system", "Системная проблема, попробуйте еще раз.");
		}

	} else {
		$JSON->write("system", "Системная проблема, попробуйте позже (#0).");
	}

	Main::fatalError('regListener', $JSON->pop());
}
