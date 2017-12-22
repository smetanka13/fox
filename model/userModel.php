<?php

class User {
 
    private static $data = [
        'logged' => FALSE
    ];

    public static function registrate($login, $email, $pass, $confirm) {

        if(!preg_match("/^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})$/is", $email)) //
            throw new Exception("Неверный формат email.");

        if(Main::select("
        	SELECT * FROM `user`
        	WHERE `email` = '$email'
            LIMIT 1
        "))
            throw new InvalidArgumentException("Этот email уже занят.");

        if(strlen($pass) <= 4)
            throw new InvalidArgumentException("Длинна пароля должна первышать 4 символа.");
        if(strlen($pass) > 32)
            throw new InvalidArgumentException("Длинна пароля не должна первышать 32 символа.");
        if (preg_match("/'\{\}\[\]\(\)\`\"/", $pass))
            throw new InvalidArgumentException("Некоторые символы в пароле не позволены."); //

        if (preg_match("/'\{\}\[\]\(\)\`\"/", $login))
            throw new InvalidArgumentException("Некоторые символы в логине не позволены."); //
        if(strlen($login) <= 6)
            throw new InvalidArgumentException("Длинна логина должна превышать 6 символов.");
        if(strlen($login) > 32)
            throw new InvalidArgumentException("Длинна логина не должна превышать 32 символов.");

        if($confirm != $pass)
            throw new InvalidArgumentException("Повторите пароль верно.");
        if(empty($confirm))
            throw new InvalidArgumentException("Повторите пароль.");

        Main::query("
            INSERT INTO `user` (
                `login`, `email`, `pass`
            ) VALUES (
                '$login', '$email', '".self::hashPass($login, $pass)."'
            )
        ");

        $key = Main::generateKey();
        try {
            Main::query("
                INSERT INTO `verify` (
                    `email`, `key`
                )
                VALUES (
                    '$email', '$key'
                )
            ");
        } catch (RuntimeException $e) {
            Main::query("
                DELETE FROM `user`
                WHERE `email` = '$email';
            ");
            throw new RuntimeException($e->getMessage());
        }

        $subject = "[".NAME."] Регистрация.";
        $headers = 'From: '.NAME. "\r\n";
        $message = "Что бы активировать ваш аккаунт пройдите по ссылке: ".URL."/remote?model=user&method=verifyEmail&key=".$key;
        if(!mail($email, $subject, $message, $headers)) {
            Main::query("
                DELETE FROM `user`
                WHERE `email` = '$email'
            ");
            Main::query("
                DELETE FROM `verify`
                WHERE `email` = '$email'
            ");
            throw new RuntimeException('Ошибка отсылки письма.');
        }
    }

    public static function verifyEmail($key) {

        $email = Main::select("
        	SELECT `email` FROM `verify`
        	WHERE `key` = '$key'
        	LIMIT 1
        ");

        if(Main::query("
        	SELECT `email` FROM `user`
        	WHERE `email` = '".$email['email']."'
        	LIMIT 1
        ")) {
        	Main::query("
        		UPDATE `user`
        		SET `confirmed` = '1'
        		WHERE `email` = '".$email['email']."'
        		LIMIT 1
        	");
        	Main::query("
        		DELETE FROM `verify`
        		WHERE `key` = '$key'
        		LIMIT 1
        	");

        	header("Location: ".URL."?msg=Вы успешно зарегистрировались.");
        } else {
        	header("Location: ".URL."?msg=Возникла проблема, попробуйте позже.");
        }
    }

    public static function saveLogged($login, $pass) {
        if(!self::login($login, $pass, TRUE))
            throw new InvalidArgumentException("Неверный логин или пароль.");

        if(!(
            setcookie('login', self::get('login'), 0, "/") &&
    		setcookie('pass', self::get('pass'), 0, "/")
        )) {
    		throw new RuntimeException("Ошибка создания куков.");
    	}
    }

    public static function hashPass($login, $pass) {
        return crypt($pass, "$6$1000$".sha1("$login~pornhub_sucks"));
    }

    public static function login($login, $pass, $hash = FALSE) {
        if($hash) $pass = self::hashPass($login, $pass);

        $user = Main::select("
            SELECT * FROM `user`
            WHERE `login` = '$login'
            AND `pass` = '$pass'
            AND `confirmed` = '1'
            LIMIT 1
        ");

        if(!empty($user)) {
            foreach($user as $key => $value) {
                self::$data[$key] = $value;
            }
            self::$data['logged'] = TRUE;

            return TRUE;
        }

        return FALSE;
    }
    public static function get($var) {
        return self::$data[$var];
    }
    public static function logged() {
        return self::$data['logged'];
    }
}
