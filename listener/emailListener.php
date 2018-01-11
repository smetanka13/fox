<?php

$key = $_GET['key'];

$email = $SQL->query("
	SELECT `email` FROM `confirm`
	WHERE `key` = '$key'
	LIMIT 0, 1
");
$email = $email -> fetch_assoc();

if($SQL->query("
	SELECT `email` FROM `users`
	WHERE `email` = '".$email['email']."'
	LIMIT 0, 1
")) {
	$SQL->query("
		UPDATE `users`
		SET `confirmed` = '1'
		WHERE `email` = '".$email['email']."'
		LIMIT 1
	");
	$SQL->query("
		DELETE FROM `confirm`
		WHERE `key` = '$key'
		LIMIT 1
	");

	header("Location: ".URL."?message=Вы успешно зарегистрировались.");
} else {
	header("Location: ".URL."?message=Возникла проблема, попробуйте позже.");
}
