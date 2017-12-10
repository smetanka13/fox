<?php

class Category {

    static public function getCategories($str = FALSE) {
        $categories = Main::select("
            SELECT `data` FROM `variable`
            WHERE `name` = 'categories'
            LIMIT 1
        ")['data'];
        if(!$str) {
            if(!empty($categories))
                return explode(';', $categories);
            else
                return [];
        } else
            return $categories;
    }
    static public function getParams($category, $str = FALSE) {

        if(!Main::lookSame(self::getCategories(), $category))
            throw new InvalidArgumentException("Категория '$category' не найдена.");

        $params = Main::select("
            SELECT `params` FROM `category_param`
            WHERE `category` = '".$category."'
            LIMIT 1
        ")['params'];

        if(!$str) {
            if(!empty($params))
                return explode(';', $params);
            else
                return [];
        } else
            return $params;
    }
    static public function getValues($category, $param, $str = FALSE) {

        if(!Main::lookSame(self::getCategories(), $category))
            throw new InvalidArgumentException("Категория '$category' не найдена.");

        if(!Main::lookSame(self::getCategories(), $category))
            throw new InvalidArgumentException("Параметра '$param' в категории '$category' не найдено.");

        $values = Main::select("
            SELECT `values` FROM `param_value`
            WHERE `category_param` = '$category/$param'
            LIMIT 1
        ")['values'];

        if(!$str) {
            if(!empty($values))
                return explode(';', $values);
            else
                return [];
        } else
            return $values;
    }
    public static function newCategory($name, $params) {
        if($params == NULL) $params = [];
        $query_str = "";

        array_unshift($params, 'Бренд');
        array_unshift($params, 'Подкатегория');

        for($i = 0; isset($params[$i]) && $params[$i] != NULL; $i++) {
            $query_str .= "`".$params[$i]."` varchar(64) NOT NULL,";
        }
        if(Main::lookSame(self::getCategories(), $name)) {
            throw new InvalidArgumentException("Категория $name уже существует.");
        }

        $categories = self::getCategories(TRUE);
        if($categories == NULL)
            $updated_categories = $name;
        else
            $updated_categories = $categories.";".$name;

        Main::query("
            UPDATE `variables`
            SET `data` = '$updated_categories'
            WHERE `name` = 'categories'
            LIMIT 1
        ");

        Main::query("
            INSERT INTO `category_param` (
                `category`
            ) VALUES (
                '$name'
            )
        ");

        self::addParams($name, $params);

        try {
            Main::query("
                CREATE TABLE `$name` (
                    id int(8) NOT NULL AUTO_INCREMENT,
                    category varchar(64) NOT NULL,
                    price float UNSIGNED NOT NULL,
                    title varchar(200) UNIQUE NOT NULL,
                    quantity int(11) UNSIGNED NOT NULL,
                    date int(20) UNSIGNED NOT NULL,
                    text mediumtext NOT NULL,
                    image varchar(32) NOT NULL,
                    video varchar(256) NOT NULL,
                    bought smallint UNSIGNED NOT NULL,
                    rating tinyint UNSIGNED NOT NULL,
                    articule varchar(70) NOT NULL,
                    ".$query_str."
                    PRIMARY KEY (id)
                )
            ");
        } catch (RuntimeException $e) {
            Main::query("
                DELETE FROM `category_param`
                WHERE `category` = '$name'
                LIMIT 1
            ");
            Main::query("
                UPDATE `variables`
                SET `data` = '".$categories."'
                WHERE `name` = 'categories'
                LIMIT 1
            ");
            throw new RuntimeException($e->getMessage());
        }

        if(!is_dir("./../catalog/".$name))
            mkdir("./../catalog/".$name);
    }
    static public function addParams($category, $params, $update = TRUE) {

        $params = is_array($params) ? $params : [$params];

        if(!Main::lookSame(self::getCategories(), $category)) {
            throw new Exception("No category '$category' found.");
        }
        foreach ($params as $param) {
            if(!$update && Main::lookSame(self::getParams($category), $param)) {
                return;
            }
        }
        if($update) {
            Main::query("
                UPDATE `category_param`
                SET `params` = '".implode(';', $params)."'
                WHERE `category` = '$category'
                LIMIT 1
            ");
        } else {
            Main::query("
                UPDATE `category_param`
                SET `params` = '".implode(';', array_merge(self::getParams($category), $params))."'
                WHERE `category` = '$category'
                LIMIT 1
            ");
        }
        foreach($params as $param) {
            Main::query("
                INSERT INTO `param_value` (
                    `category_param`
                ) VALUES (
                    '$category/$param'
                )
            ");
        }
    }
    static public function addValues($category, $param, $values, $update = TRUE) {

        $values = is_array($values) ? $values : [$values];

        if(!Main::lookSame(self::getCategories(), $category)) {
            throw new InvalidArgumentException("Не найдено категории '$category'.");
        }
        if(!Main::lookSame(self::getParams($category), $param)) {
            throw new InvalidArgumentException("Параметра '$param' не найдено в категории '$category'.");
        }
        foreach ($values as $value) {
            if(!$update && Main::lookSame(self::getValues($category, $param), $value)) {
                return;
            }
        }

        if($update) {
            Main::query("
                UPDATE `param_value`
                SET `values` = '".implode(';', $values)."'
                WHERE `category_param` = '$category/$param'
                LIMIT 1
            ");
        } else {
            Main::query("
                UPDATE `param_value`
                SET `values` = '".implode(';', array_merge(self::getValues($category, $param), $values))."'
                WHERE `category_param` = '$category/$param'
                LIMIT 1
            ");
        }
    }

    static public function getFullCategory($category) {
        $arr = [];
        $params = Category::getParams($category);
        if(!empty($params[0])) {
            for($i = 0; isset($params[$i]); $i++) {
                $arr[$params[$i]] = Category::getValues($category, $params[$i]);
            }
            return $arr;
        } else {
            throw new InvalidArgumentException("Не найдено категории '$category'.");
        }
    }
}
