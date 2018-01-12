<?php

class Category {

    private static $category_cache;
    private static $param_cache;
    private static $value_cache;

    public static function checkCategory($category, $exception = FALSE) {
        if(array_search($category, self::getCategories()) === FALSE) {
            if($exception)
                throw new InvalidArgumentException("Не найдено категории '$category'.");
            else
                return FALSE;
        }
        return TRUE;
    }
    public static function checkParam($category, $param, $exception = FALSE) {
        if(array_search($param, self::getParams($category)) === FALSE) {
            if($exception)
                throw new InvalidArgumentException("Параметра '$param' не найдено в категории '$category'.");
            else
                return FALSE;
        }
        return TRUE;
    }

    static public function getCategories($str = FALSE) {

        if(empty(self::$category_cache))
            self::$category_cache = FW::$DB->get('variable', 'data', [
                'name' => 'categories'
            ]);

        if(!$str) {
            if(!empty(self::$category_cache))
                return explode(';', self::$category_cache);
            else
                return [];
        } else
            return self::$category_cache;
    }
    static public function getParams($category, $str = FALSE) {

        if(empty(self::$param_cache[$category]))
            self::$param_cache[$category] = FW::$DB->get('category_param', 'params', [
                'category' => $category
            ]);

        if(!$str) {
            if(!empty(self::$param_cache[$category]))
                return explode(';', self::$param_cache[$category]);
            else
                return [];
        } else
            return self::$param_cache[$category];
    }
    static public function getValues($category, $param, $str = FALSE) {

        if(empty(self::$value_cache[$category][$param]))
            self::$value_cache[$category][$param] = FW::$DB->get('param_value', 'values', [
                'category_param' => "$category/$param"
            ]);

        if(!$str) {
            if(!empty(self::$value_cache[$category][$param]))
                return explode(';', self::$value_cache[$category][$param]);
            else
                return [];
        } else
            return self::$value_cache[$category][$param];
    }
    public static function newCategory($name, $params) {
        if($params == NULL) $params = [];

        array_unshift($params, 'Бренд');
        array_unshift($params, 'Подкатегория');

        if(array_search($name, self::getCategories())) {
            throw new InvalidArgumentException("Категория $name уже существует.");
        }

        $categories = self::getCategories(TRUE);
        if($categories == NULL)
            $updated_categories = $name;
        else
            $updated_categories = $categories.';'.$name;

        FW::$DB->action(function() {
            FW::$DB->update('variables', [
                'data' => $updated_categories
            ], [
                'name' => 'categories'
            ]);

            FW::$DB->insert('category_param', [
                'category' => $name
            ]);

            self::addParams($name, $params);

            $query = '';
            foreach($params as $i => $param) {
                $query .= "`$param` VARCHAR(32) NOT NULL,";
            }

            FW::$DB->query("
                CREATE TABLE `$name` (
                    id_prod INT NOT NULL AUTO_INCREMENT,
                    category VARCHAR(64) NOT NULL,
                    price FLOAT UNSIGNED NOT NULL,
                    title VARCHAR(64) UNIQUE NOT NULL,
                    quantity SMALLINT UNSIGNED NOT NULL,
                    date INT(12) UNSIGNED NOT NULL,
                    text MEDIUMTEXT NOT NULL,
                    image VARCHAR(32) NOT NULL,
                    video VARCHAR(256) NOT NULL,
                    bought SMALLINT UNSIGNED NOT NULL,
                    rating TINYINT UNSIGNED NOT NULL,
                    articule VARCHAR(70) NOT NULL,
                    discount BOOLEAN NOT NULL,
                    discount_val FLOAT NOT NULL,
                    $query
                    PRIMARY KEY (id)
                )
            ");
        });

        if(!is_dir('material/catalog/' . $name))
            mkdir('material/catalog/' . $name);
    }
    static public function addParams($category, $params, $update = TRUE) {

        $params = is_array($params) ? $params : [$params];

        self::checkCategory($category, TRUE);
        foreach ($params as $param) {
            if(!$update && array_search($param, self::getParams($category))) {
                return;
            }
        }

        $mode = $update ? implode(';', $params) : implode(';', array_merge(self::getParams($category), $params));

        FW::$DB->action(function() {

            $query = '';
            foreach($params as $i => $param) {
                $query .= "ADD `$param` VARCHAR(32) NOT NULL,";
            }

            FW::$DB->query("
                ALTER TABLE `Масла`
                $query
            ");

            FW::$DB->update('category_param', [
                'params' => $mode
            ], [
                'category' => $category
            ]);

            foreach($params as $param) {
                FW::$DB->insert('param_value', [
                    'category_param' => "$category/$param"
                ]);
            }
        });
    }
    static public function addValues($category, $param, $values, $update = TRUE) {

        $values = is_array($values) ? $values : [$values];

        self::checkCategory($category, TRUE);
        self::checkParam($category, $param, TRUE);

        foreach ($values as $value) {
            if(!$update && array_search($value, self::getValues($category, $param))) {
                return;
            }
        }

        $mode = $update ? implode(';', $values) : implode(';', array_merge(self::getValues($category, $param), $values));

        FW::$DB->update('param_value', [
            'values' => $mode
        ], [
            'category_param' => "$category/$param"
        ]);
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
