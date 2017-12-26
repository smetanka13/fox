<?php

class User {

    private static $data = [
        'logged' => FALSE
    ];


    private static function checkPass($pass) {

        if(mb_strlen($pass) < 8)
            throw new InvalidArgumentException("Длинна пароля должна быть более 8 символов.");
        if(mb_strlen($pass) > 32)
            throw new InvalidArgumentException("Длинна пароля должна быть менее 32 символов.");
        if(preg_match("/'\{\}\[\]\(\)\`\"/", $pass))
            throw new InvalidArgumentException("Некоторые символы недопустимы.");
    }
    private static function checkLogin($login) {
        if(preg_match("/'\{\}\[\]\(\)\`\"/", $login))
            throw new InvalidArgumentException("Некоторые символы недопустимы.");
        if(mb_strlen($pass) < 8)
            throw new InvalidArgumentException("Длинна логина должна быть более 8 символов.");
        if(mb_strlen($pass) > 32)
            throw new InvalidArgumentException("Длинна логина должна быть менее 32 символов.");
    }

    public static function recoverPass($email) {

        $id_user = Main::select("
        	SELECT `id` FROM `user`
        	WHERE `email` = '$email'
            LIMIT 1
        ")['id'];

        if(empty($id_user))
            throw new InvalidArgumentException("No user found with that email.");

        $key = Main::generateKey();
        Main::query("
            INSERT INTO `recover` (
                `id_user`, `key`
            )
            VALUES (
                '$id_user', '$key'
            )
        ");

        $subject = "[".NAME."] Восстановление пароля.";
        $headers = 'From: '.NAME. "\r\n";
        $message = "Что бы восстановить пароль пройдите по ссылке: ".URL."?modal=changepass&key=$key";
        if(!mail($email, $subject, $message, $headers)) {
            Main::query("
                DELETE FROM `recover`
                WHERE `id_user` = '$id_user'
                LIMIT 1
            ");
            throw new RuntimeException('Error sending mail.');
        }
    }

    public static function changePass($key, $pass, $confirm) {

        self::checkPass($pass);

        if($confirm != $pass)
            throw new InvalidArgumentException("Repeat password correctly.");
        if(empty($confirm))
            throw new InvalidArgumentException("Repeat password.");

        $id_user = Main::select("
            SELECT `id_user` FROM `recover`
            WHERE `key` = '$key'
            LIMIT 1
        ")['id_user'];

        if(empty($id_user))
            throw new InvalidArgumentException("Wrong key.");

        Main::query("
            UPDATE `user`
            SET `pass` = '".self::hashPass($login, $pass)."'
            WHERE `id` = '$id_user'
            LIMIT 1
        ");
        Main::query("
            DELETE FROM `recover`
            WHERE `id_user` = '$id_user'
            LIMIT 1
        ");
    }

    public static function registrate($login, $email, $pass, $confirm) {

        if(!preg_match("/^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})$/is", $email))
            throw new InvalidArgumentException("Неверный формат почты.");

        if(Main::select("
        	SELECT * FROM `user`
        	WHERE `email` = '$email'
            LIMIT 1
        "))
            throw new InvalidArgumentException("Эта почта уже занята.");

        self::checkPass($pass);
        self::checkLogin($login);

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
        $message = "Что бы активировать ваш аккаунт пройдите по ссылке: ".URL."/remote?model=user&method=verify&key=".$key;
        if(!mail($email, $subject, $message, $headers)) {
            Main::query("
                DELETE FROM `user`
                WHERE `email` = '$email'
            ");
            Main::query("
                DELETE FROM `verify`
                WHERE `email` = '$email'
            ");
            throw new RuntimeException('Error sending mail.');
        }
    }

    public static function verify($key) {

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
    		throw new RuntimeException("Cookie error.");
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
        if(self::$data['logged']) return self::$data[$var];
        else return FALSE;
    }
    public static function logged() {
        return self::$data['logged'];
    }
}
