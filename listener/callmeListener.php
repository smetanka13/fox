<?php

if(!empty($_POST['phone'])) {

    $SQL->query("
        INSERT INTO `call_me` (
            `phone`
        ) VALUES (
            '".$_POST['phone']."'
        )
    ");

} else {
    $JSON->write('phone', 'Укажите ваш номиер телефона.');
}
