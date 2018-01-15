<?php

class Order {
    public static function getProds($id_order) {
        return FW::$DB->select('order_prod', '*', [
            'id_order' => $id_order
        ]);
    }
    public static function getFullProds($id_order) {
        $order_prods = FW::$DB->select('order_prod', '*', [
            'id_order' => $id_order
        ]);

        foreach($order_prods as $index => $value) {

            $order_prods[$index] = FW::$DB->get($value['category'], [
                'title',
                'price',
                'articule'
            ], [
                'id_prod' => $value['id_prod']
            ]);

            $order_prods[$index]['quantity'] = $value['quantity'];
        }
        return $order_prods;
    }
    public static function getUnaccepted() {
        $orders = FW::$DB->select('order', '*', [
            'ok' => 0
        ]);

        foreach($orders as $index => $value) {
            $orders[$index]['prods'] = self::getFullProds($value['id_order']);
        }

        return $orders;
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
        if(empty(json_decode($_COOKIE['cart'], TRUE)))
            throw new InvalidArgumentException("Карзина пустая.");

        require_once 'model/productModel.php';

        FW::$DB->action(function() use($pay_way, $delivery_way, $public, $city, $address, $email, $phone, $text) {

            FW::$DB->insert('order', [
                'public' => $public,
                'pay_way' => $pay_way,
                'delivery_way' => $delivery_way,
                'city' => $city,
                'address' => $address,
                'email' => $email,
                'phone' => $phone,
                'text' => $text,
                'date' => TIME,
                'price' => Product::getFullPriceCookie($_COOKIE['cart'])
            ]);

            $id_order = FW::$DB->id();

            $query = [];
            foreach(array_values(json_decode($_COOKIE['cart'], TRUE)) as $index => $value) {
                $query[] = [
                    'id_order' => $id_order,
                    'id_prod' => $value['id_prod'],
                    'quantity' => $value['quantity'],
                    'category' => $value['category']
                ];
            }

            FW::$DB->insert('order_prod', $query);

            if(User::logged()) {
<<<<<<< HEAD

=======
                User::addOrder($id_order);
>>>>>>> 3a60896a3200ac8ec5cfa54f1ddba7bd1490d399
            }

        });

    }
    public static function check() {
        FW::$DB->update('order', [
            'checked' => 1
        ], [
            'checked' => 0
        ]);
    }
    public static function accept($id_order) {
        FW::$DB->update('order', [
            'ok' => 1
        ], [
            'id_order' => $id_order
        ]);
        $prods = self::getProds($id_order);

        foreach ($prods as $i => $prod) {
            FW::$DB->query("
                UPDATE {$prod['category']}
                SET bought = bought + 1
                WHERE id_prod = {$prod['id_prod']}
                LIMIT 1
            ");
        }
    }
    public static function delete($id_order) {
        FW::$DB->delete('order', [
            'id_order' => $id_order
        ]);
        FW::$DB->delete('order_prod', [
            'id_order' => $id_order
        ]);
        User::deleteOrder($id_order);
    }
    public static function getAccepted() {
        $orders = FW::$DB->select('order', '*', [
            'ok' => 1
        ]);

        foreach($orders as $index => $value) {
            $orders[$index]['prods'] = self::getFullProds($value['id_order']);
        }

        return $orders;
    }
}
