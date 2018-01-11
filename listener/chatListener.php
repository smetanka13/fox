<?php

$text = $_POST['text'];
$mark = $_POST['mark'];
$name = $_POST['name'];

if($mark > 5) $mark = 5;
if(empty($name)) $name = "Аноним";

if($mark <= 0)
    $JSON->write('mark', 'Как вы оцените нас?');
else {
    if(!$SQL->query("
        INSERT INTO `chat` (`text`, `mark`, `name`)
        VALUES ('$text', '$mark', '$name')
    ")) {
        $JSON->write('system', 'Системная проблема, попробуйте позже.');
		Main::fatalError('chatListener', $JSON->pop());
    }
}
