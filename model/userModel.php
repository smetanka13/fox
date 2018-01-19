<?php

class User {

    private static $data = [
        'logged' => FALSE
    ];

    public static function getOrders() {

        require_once 'model/orderModel.php';

        $orders = FW::$DB->select('user_order', [
            '[<>]order' => ['id_order' => 'id_order']
        ], '*', [
            'id_user' => User::get('id_user')
        ]);

        foreach ($orders as $key => $value) {
            $orders[$index]['prods'] = Order::getFullProds($value['id_order']);
        }
    }

    public static function addOrder($id_order) {
        FW::$DB->insert('user_order', [
            'id_user' => User::get('id_user'),
            'id_order' => $id_order
        ]);
    }

    public static function deleteOrder($id_order) {
        FW::$DB->delete('user_order', [
            'id_user' => User::get('id_user'),
            'id_order' => $id_order
        ]);
    }

    public static function updateFavorite($category, $id_prod) {

        require_once 'model/productModel.php';

        if(!Product::getById($category, $id_prod))
            throw new InvalidArgumentException("В категории '$category' нету продукта с айди $id_prod.");

        if(FW::$DB->has('user_favorite', [
            'category' => $category,
            'id_prod' => $id_prod,
            'id_user' => self::get('id_user')
        ])) {

            FW::$DB->delete('user_favorite', [
                'category' => $category,
                'id_prod' => $id_prod,
                'id_user' => self::get('id_user')
            ]);

            return 'deleted';

        } else {

            FW::$DB->insert('user_favorite', [
                'category' => $category,
                'id_prod' => $id_prod,
                'id_user' => self::get('id_user')
            ]);

            return 'added';

        }

    } 
    public static function getFavorite() {

        require_once 'model/productModel.php';

        $fav = FW::$DB->select('user_favorite', '*', [
            'id_user' => self::get('id_user')
        ]);

        return Product::selectFromDiffCategories($fav);

    }

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
        if(mb_strlen($login) < 8)
            throw new InvalidArgumentException("Длинна логина должна быть более 8 символов.");
        if(mb_strlen($login) > 32)
            throw new InvalidArgumentException("Длинна логина должна быть менее 32 символов.");
    }

    public static function recoverPass($email) {

        $id_user = FW::$DB->get('user', 'id', [
            'email' => $email
        ]);

        if(empty($id_user))
            throw new InvalidArgumentException("No user found with that email.");

        $key = FW::generateKey();

        FW::$DB->insert('recover', [
            'id_user' => $id_user,
            'key' => $key
        ]);

        $subject = "[".NAME."] Восстановление пароля.";
        $headers = 'From: '.NAME. "\r\n";
        $message = "Что бы восстановить пароль пройдите по ссылке: ".URL."?modal=changepass&key=$key";
        if(!mail($email, $subject, $message, $headers)) {
            FW::$DB->delete('recover', [
                'id_user' => $id_user
            ]);
            throw new RuntimeException('Error sending mail.');
        }
    }

    public static function changePassKey($key, $pass, $confirm) {

        self::checkPass($pass);

        if($confirm != $pass)
            throw new InvalidArgumentException("Repeat password correctly.");
        if(empty($confirm))
            throw new InvalidArgumentException("Repeat password.");

        $id_user = FW::$DB->get('recover', 'id_user', [
            'key' => $key
        ]);

        if(empty($id_user))
            throw new InvalidArgumentException("Wrong key.");

        FW::$DB->update('user', [
            'pass' => self::hashPass($login, $pass)
        ], [
            'id_user' => $id_user
        ]);


        $id_user = FW::$DB->delete('recover', [
            'id_user' => $id_user
        ]);
    }

    public static function changePass($pass, $confirm) {

        self::checkPass($pass);

        if($confirm != $pass)
            throw new InvalidArgumentException("Repeat password correctly.");
        if(empty($confirm))
            throw new InvalidArgumentException("Repeat password.");


        FW::$DB->update('user', [
            'pass' => self::hashPass($login, $pass)
        ], [
            'id_user' => $id_user
        ]);


        $id_user = FW::$DB->delete('recover', [
            'id_user' => $id_user
        ]);
    }

    public static function registrate($login, $email, $pass, $confirm) {

        if(!preg_match("/^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})$/is", $email))
            throw new InvalidArgumentException("Неверный формат почты.");

        if(FW::$DB->get('recover', '*', [
            'email' => $email
        ]))
            throw new InvalidArgumentException("Эта почта уже занята.");

        self::checkPass($pass);
        self::checkLogin($login);

        if($confirm != $pass)
            throw new InvalidArgumentException("Повторите пароль верно.");
        if(empty($confirm))
            throw new InvalidArgumentException("Повторите пароль.");

        FW::$DB->action(function() use($login, $pass, $email) {

            FW::$DB->insert('user', [
                'login' => $login,
                'email' => $email,
                'pass' => self::hashPass($login, $pass)
            ]);

            $key = FW::generateKey();
            FW::$DB->insert('user_verify', [
                'key' => $key,
                'email' => $email
            ]);

            $subject = "[".NAME."] Регистрация.";
            $headers = 'From: '.NAME. "\r\n";
            $message = "Что бы активировать ваш аккаунт пройдите по ссылке: ".URL."/remote?model=user&method=verify&key=".$key;
            if(!mail($email, $subject, $message, $headers)) {
                throw new RuntimeException('Error sending mail.');
            }

        });
    }

    public static function verify($key) {

        $email = FW::$DB->get('user_verify', 'email', [
            'key' => $key
        ]);

        if(FW::$DB->has('user', [
            'email' => $email
        ])) {
            FW::$DB->update('user', [
                'confirmed' => 1
            ], [
                'email' => $email
            ]);

            FW::$DB->delete('user_verify', [
                'key' => $key
            ]);

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

        $user = FW::$DB->get('user', '*', [
            'login' => $login,
            'pass' => $pass,
            'confirmed' => 1
        ]);

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
