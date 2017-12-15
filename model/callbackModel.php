<?php

class Callback {
    public static function getUnchecked() {
        return Main::select("
            SELECT * FROM `order`
            WHERE `checked` = '0'
        ", TRUE);
    }
    public static function add($phone) { // FIXME: 

        if(empty($phone))
            throw new InvalidArgumentException("Введите ФИО.");

        Main::query("
            INSERT INTO `bacll` (
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
