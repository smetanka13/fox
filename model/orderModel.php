<?php

class Order {
    public static function getProds($id_order) {
        return Main::select("
            SELECT * FROM `order_prod`
            WHERE `id_order` = '$id_order'
        ", TRUE);
    }
    public static function getUnaccepted() {
        $order = Main::select("
            SELECT * FROM `order`
            WHERE `ok` = '0'
        ", TRUE);

        foreach($order as $value) {
            $order['prods'] = self::getProds($order['id_order']);
        }

        return $order;
    }
    public static function add($pay_way, $delivery_way, $public, $city, $address, $email, $phone, $text) {

        if(empty($public))
            throw new InvalidArgumentException("Введите ФИО.");
        if(empty($pay_way))
            throw new InvalidArgumentException("Укажите способ оплаты.");
        if(empty($delivery_way))
            throw new InvalidArgumentException("Укажите способ доставки.");
        if(empty($city))
            throw new InvalidArgumentException("Укажите город.");
        if(empty($address))
            throw new InvalidArgumentException("Введите адрес.");
        if(empty($phone))
            throw new InvalidArgumentException("Введите ваш номер телефона.");
        if(!preg_match("/^(\+([0-9]{1,2}) (\([0-9]{3}\)) ([0-9]{3})\-([0-9]{2})\-([0-9]{2}))$/is", $phone))
            throw new InvalidArgumentException("Неверный формат номера.");

        require_once 'model/productModel.php';

        $cookie_json = array_values(json_decode($_COOKIE['cart'], TRUE));

        Main::query("
            INSERT INTO `order` (
                `public`, `pay_way`, `delivery_way`,
                `city`, `address`, `email`,
                `phone`, `text`, `date`, `price`
            ) VALUES (
                '$public', '$pay_way', '$delivery_way',
                '$city', '$address', '$email',
                '$phone', '$text', '".TIME."', '".Product::getFullPriceCookie($cookie_json)."'
            )
        ");

        $order_id = Main::select("
            SELECT `id_order` FROM `order`
            WHERE `city` = '$city'
            AND `address` = '$address'
            AND `email` = '$email'
            AND `phone` = '$phone'
            AND `date` = '".TIME."'
            LIMIT 1
        ")['id_order'];

        $query = '';
        foreach($cookie_json as $index => $value) {
            if($index > 0) $query .= ',';
            $query .= "('$order_id', '{$value['id_prod']}', '{$value['quantity']}', '{$value['category']}')";
        }

        try {
            Main::query("
                INSERT INTO `order_prod` (
                    `id_order`, `id_prod`, `quantity`, `category`
                ) VALUES $query
            ");
        } catch (RuntimeException $e) {
            Main::query("
                INSERT INTO `order_prod` (
                    `id_prod`, `quantity`, `category`
                ) VALUES $query
            ");
            throw new RuntimeException($e->getMessage());
        }


    }
    public static function check() {
        Main::query("
            UPDATE `order`
            SET `checked` = '1'
            WHERE `checked` = '0'
        ");
    }
    public static function accept($id_order) {
        Main::query("
            UPDATE `order`
            SET `ok` = '1'
            WHERE `id` = '$id'
            LIMTI 1
        ");
    }
}
