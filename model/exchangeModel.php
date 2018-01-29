<?php

class Exchange {

    public static function get() {

        $data = json_decode(FW::$DB->get('variable', 'data', [
            'name' => 'exchange'
        ]), TRUE);

        $data['EUR'] = 1;

        return $data;
    }

    public static function set($data) {
        FW::$DB->update('variable', [
            'data' => json_encode($data)
        ], [
            'name' => 'exchange'
        ]);

        FW::$DB->insert('exchange_log', [
            'data' => json_encode($data),
            'date' => TIME
        ]);
    }


}
