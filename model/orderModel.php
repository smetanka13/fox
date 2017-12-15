<?php

class Order {
    public static function getUnchecked() {
        return Main::select("
            SELECT * FROM `order`
            WHERE `checked` = '0'
        ", TRUE);
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

        Main::query("
            INSERT INTO `buyers` (
                `public`, `pay_way`, `delivery_way`,
                `city`, `address`, `email`,
                `phone`, `text`, `date`
            ) VALUES (
                '$public', '$pay_way', '$delivery_way',
                '$city', '$address', '$email',
                '$phone', '$text', '".TIME."'
            )
        ");
    }
    public static function check() {
        Main::query("
            UPDATE `order`
            SET `checked` = '1'
            WHERE `checked` = '0'
        ");
    }
}
