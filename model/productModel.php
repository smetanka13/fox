<?php

require_once 'model/categoryModel.php';

class Product {

    private static $default_values = [
        'id_prod',
        'category',
        'price',
        'title',
        'quantity',
        'date',
        'text',
        'image',
        'video',
        'bought',
        'rating',
        'articule',
        'params'
    ];

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
            SELECT `id_prod` FROM `$category`
            WHERE `title` = '$title'
            LIMIT 1
        ")['id_prod'], $category);
    }
    public static function update($id_prod, $articule, $title, $price, $category, $spec = []) {

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
            WHERE `id_prod` = '$id_prod'
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
            WHERE `id_prod` = '$id_prod'
            LIMIT 1
        ");

    }

    public static function selectFromDiffCategories($prods) {

        if(empty($prods)) return;

        $query = '';
        if(count($prods) == 1) {
            $query = "
                SELECT * FROM `".$prods[0]['category']."`
                WHERE `id_prod` = '".$prods[0]['id_prod']."'
                LIMIT 1
            ";
        } else {
            foreach($prods as $index => $prod) {
                if($index != 0) $query .= ' UNION ';
                $query .= "(
                    SELECT * FROM `".$prod['category']."`
                    WHERE `id_prod` = '".$prod['id_prod']."'
                    LIMIT 1
                )";
            }
        }

        return self::processProdParams(Main::select($query, TRUE), TRUE);

    }

    public static function processProdParams($input, $array = FALSE) {

        if($array) {

            foreach($input as $i => $prod) {
                $input[$i]['params'] = [];
                foreach($prod as $key => $value) {
                    if(!Main::lookSame(self::$default_values, $key)) {
                        $input[$i]['params'][$key] = $value;
                        unset($input[$i][$key]);
                    }
                }
            }

        } else {

            $input['params'] = [];
            foreach($input as $key => $value) {
                if(!Main::lookSame(self::$default_values, $key)) {
                    $input['params'][$key] = $value;
                    unset($input[$key]);
                }
            }

        }

        return $input;
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
            WHERE `id_prod` = '$id_prod'
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
                WHERE `id_prod` = '$id_prod'
                LIMIT 1
            ");
        } catch (RuntimeException $e) {
            unlink($full_path);
            throw new RuntimeException($e->getMessage());
        }

    }
    public static function getApprox($category, $str) {

        require_once 'model/searchModel.php';

        if(empty($str)) return FALSE;

        if($str[0] == ':') {
            return self::processProdParams(Main::select("
                SELECT * FROM `$category`
                WHERE `articule` = '".substr($str, 1)."'
                LIMIT 1
            "));
        } else {
            return Search::find($str, $category)[0];
        }
    }
    public static function getFullPriceCookie($cookie) {

        $cookie = json_decode($cookie, JSON_UNESCAPED_UNICODE);

        $prods = self::selectFromDiffCategories($cookie);

        $price = 0;
        foreach($prods as $index => $prod) {
            $price += $prod['price'] * $cookie[$index]['quantity'];
        }
        return $price;
    }
    public static function getById($category, $id_prod) {

        if(is_array($id_prod)) {

            $query = '';
            foreach($input as $i => $value) {
                if($i > 0) $query .= " OR ";
                $query .= " `id_prod` = '$value' ";
            }

            $result = Main::select("
                SELECT * FROM `$category`
                WHERE $query
            ", TRUE);
        } else {
            $result = Main::select("
                SELECT * FROM `$category`
                WHERE `id_prod` = '$id_prod'
                LIMIT 1
            ");
        }

        return self::processProdParams($result, is_array($id_prod));
    }
}
