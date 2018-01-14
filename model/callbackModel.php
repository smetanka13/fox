<?php

class Callback {
    public static function get() {
        return FW::$DB->select('callback', '*', [
            'phone' => $phone
        ]);
    }
    public static function add($phone) {

        $is_busy = FW::$DB->get('callback', 'id', [
            'phone' => $phone,
            'checked' => 0
        ]);

        if(!empty($is_busy))
            throw new InvalidArgumentException("Вы уже отправили свой номер, сейчас оператор наберет вас.");
        if(empty($phone))
            throw new InvalidArgumentException("Введите ваш номер.");
        if(!preg_match("/^(\+([0-9]{1,2}) (\([0-9]{3}\)) ([0-9]{3})\-([0-9]{2})\-([0-9]{2}))$/is", $phone))
            throw new InvalidArgumentException("Неверный формат номера.");

        FW::$DB->insert('callback', [
            'phone' => $phone,
            'date' => TIME
        ]);

    }
    public static function delete($phone) {
        FW::$DB->delete('callback', [
            'phone' => $phone
        ]);
    }
}
