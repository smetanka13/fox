<?php

require_once 'model/categoryModel.php';

$category = $_POST['category'];
$param = $_POST['param'];
$values = $_POST['values'];

if(preg_match('/\//', $values))
    $JSON->write("values", "В значениях нельзя использовать символы '/'. Используйте знак '\\' вместо.");

try {
    Category::addValues($category, $param, explode('/', $values));
} catch (Exception $e) {
    $JSON->write("error", $e->getMessage());
}
