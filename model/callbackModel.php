<?php

class Callback {
    public static function getUnchecked() {
        return Main::select("
            SELECT * FROM `callback`
            WHERE `checked` = '0'
        ", TRUE);
    }
    public static function add($phone) {

        $is_busy = Main::select("
            SELECT `id` FROM `callback`
            WHERE `phone` = '$phone'
            AND `checked` = '0'
            LIMIT 1
        ");

        if(!empty($is_busy))
            throw new InvalidArgumentException("Вы уже отправили свой номер, сейчас оператор наберет вас.");
        if(empty($phone))
            throw new InvalidArgumentException("Введите ваш номер.");
        if(!preg_match("/^(\+([0-9]{1,2}) (\([0-9]{3}\)) ([0-9]{3})\-([0-9]{2})\-([0-9]{2}))$/is", $phone))
            throw new InvalidArgumentException("Неверный формат номера.");

        Main::query("
            INSERT INTO `callback` (
                `phone`, `date`
            ) VALUES (
                '$phone', '".TIME."'
            )
        ");
    }
    public static function check() {
        Main::query("
            UPDATE `callback`
            SET `checked` = '1'
            WHERE `checked` = '0'
        ");
    }
}
