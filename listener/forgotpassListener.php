<?php

$email = $_GET['email'];

if() {
    $key = base64_encode(Main::hashPass($pass));
    $subject = "[".URL."] регистрация.";
    $headers = 'From: '.URL. "\r\n";
    $message = "Для активации вашего аккаунта пройдите по ссылке: ".URL."/listener/listenerController.php?listener=email&key=".$key;
    if(!mail($email, $subject, $message, $headers)) {
        $SQL->query("
            DELETE FROM `users`
            WHERE `email` = '$email';
        ");
        $JSON->write("system", "Системная проблема, попробуйте позже (#1).");
    }
} else {

}
