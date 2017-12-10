<?php

$cart = json_decode($_COOKIE['cart'], TRUE);

if(!empty($cart)) {

    if(empty($_POST['public'])) $JSON->write('public', 'Введите ФИО.');
    if(empty($_POST['pay_way'])) $JSON->write('pay_way', 'Укажите способ оплаты.');
    if(empty($_POST['delivery_way'])) $JSON->write('delivery_way', 'Укажите способ доставки.');
    if(empty($_POST['city'])) $JSON->write('city', 'Укажите город.');
    if(empty($_POST['address'])) $JSON->write('address', 'Введите адрес.');
    if(empty($_POST['phone'])) $JSON->write('phone', 'Введите ваш номер телефона.');

    if($JSON->ok()) {
        $SQL->query("
            INSERT INTO `buyers` (
                `public`, `pay_way`, `delivery_way`,
                `city`, `address`, `email`,
                `phone`, `text`, `date`
            ) VALUES (
                '".$_POST['public']."', '".$_POST['pay_way']."', '".$_POST['delivery_way']."',
                '".$_POST['city']."', '".$_POST['address']."', '".$_POST['email']."',
                '".$_POST['phone']."', '".$_POST['text']."', '".TIME."'
            )
        ");
    }

} else {
    $JSON->write('cart', 'Корзина пустая.');
}
