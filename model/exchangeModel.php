<?php

class Exchange {

    public static function get() {
        return json_decode(FW::$DB->get('variable', 'data', [
            'name' => 'exchange'
        ]), TRUE);
    }

    public static function set($data) {
        FW::$DB->get('variable', [
            'data' => json_encode($data)
        ], [
            'name' => 'exchange'
        ]);
    }


}
