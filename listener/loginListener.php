<?php

if($User->login(NULL, $_POST['email'], $_POST['pass'], TRUE)) {

	if(!(setcookie('email', $User->get('email'), TIME+3600, "/")
		&& setcookie('pass', $User->get('pass'), TIME+3600, "/")
		&& setcookie('id', $User->get('id'), TIME+3600, "/"))
	) {
		$JSON->write("user", "Ошибка. Попробуйте отчистить куки и кэш вашего браузера.");
		Main::fatalError('loginListener', $JSON->pop());
	}

} else {
	$JSON->write("user", "Неверный email или пароль.");
}
