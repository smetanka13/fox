<?php

class Category {

    private static $category_cache;
    private static $param_cache;
    private static $value_cache;

    public static function checkCategory($category, $return = FALSE) {
        if(array_search($category, self::getCategories()) === FALSE) {
            if(!$return)
                throw new InvalidArgumentException("Не найдено категории '$category'.");
            else
                return FALSE;
        }
        return TRUE;
    }
    public static function checkParam($category, $param, $return = FALSE) {
        if(array_search($param, self::getParams($category)) === FALSE) {
            if(!$return)
                throw new InvalidArgumentException("Параметра '$param' не найдено в категории '$category'.");
            else
                return FALSE;
        }
        return TRUE;
    }

    static public function clearCategoriesCache() {
        self::$category_cache = NULL;
    }
    static public function clearParamsCache($category) {
        self::$param_cache[$category] = NULL;
    }
    static public function clearValuesCache($category, $param) {
        self::$value_cache[$category][$param] = NULL;
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

        if(is_string($params)) $params = [$params];

        if(empty($params)) $params = [];

        array_unshift($params, 'Бренд');
        array_unshift($params, 'Подкатегория');

        if(self::checkCategory($name, TRUE)) {
            throw new InvalidArgumentException("Категория '$name' уже существует.");
        }

        $categories = self::getCategories(TRUE);
        if($categories == NULL)
            $updated_categories = $name;
        else
            $updated_categories = $categories.';'.$name;

        self::clearCategoriesCache();

        FW::$DB->action(function() use($name, $updated_categories) {

            FW::$DB->update('variable', [
                'data' => $updated_categories
            ], [
                'name' => 'categories'
            ]);

            FW::$DB->insert('category_param', [
                'category' => $name
            ]);

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
                    articule VARCHAR(70) UNIQUE NOT NULL,
                    discount BOOLEAN NOT NULL,
                    discount_percent TINYINT NOT NULL,
                    PRIMARY KEY (id_prod)
                )
            ");

        });

        try {

            self::addParams($name, $params);

        } catch (Exception $e) {

            FW::$DB->update('variable', [
                'data' => $categories
            ], [
                'name' => 'categories'
            ]);

            FW::$DB->delete('category_param', [
                'category' => $name
            ]);

            FW::$DB->query("
                DROP TABLE `$name`
            ");

            throw $e;

        }


        if(!is_dir('material/catalog/' . $name))
            mkdir('material/catalog/' . $name);
    }
    static public function addParams($category, $params) {

        $params = is_array($params) ? $params : [$params];

        self::checkCategory($category);
        foreach ($params as $param) {
            if(self::checkParam($category, $param, TRUE)) {
                return;
            }
        }

        FW::$DB->action(function() use($category, $params) {

            $query = '';
            foreach($params as $i => $param) {
                if($i > 0) $query .= ',';
                $query .= "ADD `$param` VARCHAR(64) NOT NULL";
            }

            FW::$DB->query("
                ALTER TABLE `$category`
                $query
            ");

            FW::$DB->update('category_param', [
                'params' => implode(';', array_merge(self::getParams($category), $params))
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

        self::checkCategory($category);
        self::checkParam($category, $param);

        if(!$update) {
            foreach ($values as $value) {
                if(array_search($value, self::getValues($category, $param))) {
                    return;
                }
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
        $params = self::getParams($category);
        if(!empty($params[0])) {
            for($i = 0; isset($params[$i]); $i++) {
                $arr[$params[$i]] = self::getValues($category, $params[$i]);
            }
            return $arr;
        } else {
            throw new InvalidArgumentException("Не найдено категории '$category'.");
        }
    }
    static public function deleteParams($category, $params) {
        $params = is_array($params) ? $params : [$params];

        self::checkCategory($category);
        foreach ($params as $param) {
            if(!self::checkParam($category, $param, TRUE)) {
                return;
            }
        }

        FW::$DB->action(function() use($category, $params) {

            $query = '';
            foreach($params as $i => $param) {
                if($i > 0) $query .= ',';
                $query .= "DROP `$param` VARCHAR(64) NOT NULL";
            }

            FW::$DB->query("
                ALTER TABLE `$category`
                $query
            ");

            FW::$DB->update('category_param', [
                'params' => implode(';', array_diff(self::getParams($category), $params))
            ], [
                'category' => $category
            ]);

            foreach($params as $param) {
                FW::$DB->delete('param_value', [
                    'category_param' => "$category/$param"
                ]);
            }
        });
    }
    static public function deleteCategory($category) {

        self::checkCategory($category);

        $categories = self::getCategories();
        unset($categories[array_search($category, $categories)]);

        FW::$DB->update('variable', [
            'data' => implode(';', $categories)
        ], [
            'name' => 'categories'
        ]);

        FW::$DB->delete('category_param', [
            'category' => $category
        ]);

        FW::$DB->delete('param_value', [
            'category_param[~]' => "$category/"
        ]);

        FW::$DB->query("
            DROP TABLE `$category`
        ");
    }
}
