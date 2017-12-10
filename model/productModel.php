<?php

require_once 'model/categoryModel.php';

class Product {
    public static function add($articule, $title, $price, $category, $spec = []) {

        $quantity = Main::select("
            SELECT `quantity` FROM `category_params`
            WHERE `category` = '$category'
            LIMIT 1
        ")['quantity'];

        if(empty($quantity))
            throw new InvalidArgumentException("No category $category found.");

        $quantity++;
        Main::query("
            UPDATE `category_params`
            SET `quantity` = '$quantity'
            WHERE `category` = '$category'
            LIMIT 1
        ");

        $params_str = '';
        foreach($spec as $param => $value) {
            $params_str .= "`$param`,";
        }

        $values_str = '';
        foreach($spec as $param => $value) {
            $values_str .= "'$value',";
        }

        try {

            Main::query("
                INSERT INTO `$category` (
                    $params_str
                    `articule`,
                    `title`,
                    `price`,
                    `category`
                )
                VALUES (
                    $values_str
                    '$articule',
                    '$title',
                    '$price',
                    '$category'
                )
            ");

        } catch (RuntimeException $e) {
            $quantity--;
            Main::query("
                UPDATE `category_params`
                SET `quantity` = '$quantity'
                WHERE `category` = '$category'
            ");
            throw new RuntimeException($e->getMessage());
        }

        Tops::addNewProds(Main::select("
            SELECT `id` FROM `$category`
            WHERE `title` = '$title'
            LIMIT 1
        ")['id'], $category);
    }
    public static function update($id, $articule, $title, $price, $category, $spec = []) {

        if($articule != NULL) {
            $articule == "`articule` = '$articule',";
        } else {
            $articule == "";
        }

        $query_str = '';
        foreach($spec as $param => $value) {
            $query_str .= "`$param` = '$value', ";
        }

        Main::query("
            UPDATE `$category`
            SET $query_str
            $articule
            `title` = '$title',
            `price` = '$price',
            `category` = '$category'
            WHERE `id` = '$id'
            LIMIT 1
        ");

    }

    public static function humanUpdate($id_prod, $price, $category, $text, $spec = []) {

        $text = str_replace("\n", '<br>', $text);

        $query_str = '';
        foreach($spec as $param => $value) {
            $query_str .= "`$param` = '$value', ";
        }

        Main::query("
            UPDATE `$category`
            SET $query_str
            `price` = '$price',
            `category` = '$category',
            `text` = '$text'
            WHERE `id` = '$id_prod'
            LIMIT 1
        ");

    }

    public static function selectFromDiffCategories($prods) {

        $query = '';
        foreach($prods as $index => $prod) {
            if($index != 0) $query .= ' UNION ';
            $query .= "(
                SELECT * FROM `".$prod['category']."`
                WHERE `id` = '".$prod['id']."'
                LIMIT 1
            )";
        }
        if(count($prods) == 1) {
            $query = "
                SELECT * FROM `".$prods[0]['category']."`
                WHERE `id` = '".$prods[0]['id']."'
                LIMIT 1
            ";
        }

        if($prods != NULL) {

            $result = Main::select($query, TRUE);

            return $result;
        } else {
            return FALSE;
        }

    }

    public static function upload($category, $id_prod, $text, $price, $quantity, $params, $img) {
        $params = json_decode($params, TRUE);

        if(!Main::lookSame(Category::getCategories(), $category))
            throw new InvalidArgumentException("Категория '$category' не найдена.");

        Product::humanUpdate($id_prod, $price, $category, $text, $params);

        $img = $img[0];

        # --- IF IMAGE EXISTS, UPLOAD IT --- #
        if(empty($img)) return;

        $img_dir = __DIR__ . '/../catalog/'.$category;

        $prev_image = Main::select("
            SELECT `image` FROM `$category`
            WHERE `id` = '$id_prod'
            LIMIT 1
        ")['image'];

        $valid_ext = ['jpeg', 'png', 'jpg', 'bmp'];

        $extention = Main::getFileExt($img['name']);

        if(!Main::lookSame($valid_ext, $extention))
            throw new InvalidArgumentException("Недопустимое расширение для файла '$extention'.");

        $image = substr(sha1(TIME . $img['name']), 0, (31 - strlen($extention))) . ".$extention";

        $full_path = $img_dir . '/' . $image;

        if(!empty($prev_image) && file_exists($img_dir . '/' . $prev_image))
            unlink($img_dir . '/' . $prev_image);

        if(!move_uploaded_file($img['tmp_name'], $full_path))
            throw new RuntimeException("Не вышло загрузить фото.");

        try {
            Main::query("
                UPDATE `$category`
                SET `image` = '$image'
                WHERE `id` = '$id_prod'
                LIMIT 1
            ");
        } catch (RuntimeException $e) {
            unlink($full_path);
            throw new RuntimeException($e->getMessage());
        }

    }
    public static function get($category, $str) {

        require_once 'model/searchModel.php';

        if(empty($str)) return FALSE;

        if($str[0] == ':') {
            return Main::select("
                SELECT * FROM `$category`
                WHERE `articule` = '".substr($str, 1)."'
                LIMIT 1
            ");
        } else {
            return Search::find($str, $category)[0];
        }
    }
}
