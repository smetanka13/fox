<?php

require_once 'model/categoryModel.php';

try {
    Category::newCategory($_POST['name'], empty($_POST['params']) ? [] : explode(';', $_POST['params']));
} catch (Exception $e) {
    $JSON->write("error", $e->getMessage());
}
