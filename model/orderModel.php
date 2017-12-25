<?php

class Order {
    public static function getProds($id_order) {
        return Main::select("
            SELECT * FROM `order_prods`
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

        Main::query("
            INSERT INTO `order` (
                `public`, `pay_way`, `delivery_way`,
                `city`, `address`, `email`,
                `phone`, `text`, `date`
            ) VALUES (
                '$public', '$pay_way', '$delivery_way',
                '$city', '$address', '$email',
                '$phone', '$text', '".TIME."'
            )
        ");

        require_once 'model/productModel.php';

        $query = '';
        $cookie_json = json_decode($_COOKIE['cart'], TRUE);
        foreach($cookie_json as $index => $value) {
            if($index > 0) $query .= ',';
            $query .= "('{$value['id_prod']}', '{$_COOKIE['quantity']}', '{$_COOKIE['category']}')";
        }

        Product::selectFromDiffCategories($cookie);

        Main::query("
            INSERT INTO `order_prod` (
                `id_prod`, `quantity`, `category`
            ) VALUES $query
        ");
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
