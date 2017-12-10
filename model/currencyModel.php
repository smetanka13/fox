<?php

class Currency {
    public static function get() {
        $currency = $GLOBALS['SQL']->query("
            SELECT `data` FROM `variables`
            WHERE `name` = 'currency'
            LIMIT 0, 1
        ");
        $currency = $currency->fetch_assoc();
        return json_decode($currency['data'], TRUE);
    }
    public static function update($array) {
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);

        $GLOBALS['SQL']->query("
            UPDATE `variables`
            SET `data` = '$array'
            WHERE `name` = 'currency'
        ");
        echo $GLOBALS['SQL']->error;
    }
}
