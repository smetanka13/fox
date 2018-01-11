<?php

class OilParser {

    public static function parse() {

    }
    public static function update() {

    }
    public static function getOilsID($mark, $model) {
        $Oils = Main::select("
            SELECT `oils_id` FROM `oil_parser`
            WHERE `car` = '$mark/$model'
            LIMIT 1
        ");
        if(!empty($Oils['oils_id']))
            return explode(';', $Oils['oils_id']);
        else
            return [];
    }
    public static function getOils() {

    }
}
