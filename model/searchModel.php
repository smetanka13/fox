<?php

require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

class Search {

    private static $max_finds = 12;
    private static $max_pages = 2;

    public static function formatWordQuery($query, $category, $query_params) {
        # ---- Формирование запроса по поисковым словам ---- #

        Category::checkCategory($category, TRUE);

        $query_title = '';
        $query_text = '';
        $quated_words = [];

        $words = explode(" ", $query);
        foreach($words as $i => $word) {
            if($i > 0) {
                $query_title .= ' AND ';
                $query_text .= ' AND ';
            }
            $quated_words[$i] = FW::$DB->quote("%$word%");
            $query_title .= "title LIKE $quated_words[$i]";
            $query_text .= "text LIKE $quated_words[$i]";
        }

        $list_params = Category::getParams($category);
        $params_scan = '';

        if($query_title == '')
            $query_title = "title LIKE '%%'";

        if($query_text == '')
            $query_text = "text LIKE '%%'";

        foreach($list_params as $param) {
            $tmp = "";
            foreach($words as $i => $word) {
                if($i > 0) $tmp .= ' AND ';
                $tmp .= "`$param` LIKE $quated_words[$i]";
            }

            if(empty($tmp)) break;

            $params_scan .= "
                OR (
                    ($tmp)
                    $query_params
                )
            ";
        }

        return [
            $query_title,
            $query_text,
            $params_scan
        ];
    }

    private static function formatValuesQuery($values) {
        # ---- Формирование запроса по спецификациям ---- #

        $query = '';

        if(!empty($values)) {
            $params = array_keys($values);

            foreach($params as $param) {
                Category::checkParam($param, TRUE);
                $query .= ' AND (';
                $exploaded_values = explode('/', $values[$param]);
                foreach($exploaded_values as $i => $value) {
                    if($i > 0) $query .= ' OR ';
                    $query .= "`$param` REGEXP " . FW::$DB->quote("^$value$|^$value/|/$value$|/$value/");
                }
                $query .= ')';
            }
        }

        return $query;
    }

    public static function find($page, $query, $category, $values = NULL, $sort = NULL, $direction = NULL) {

        if(is_string($values)) $values = json_decode($values, TRUE);

        if(empty($page)) $page = 0;
        if(empty($sort)) $sort = 'bought';
        if(empty($direction)) $direction = 'ASC';

        $to = (($page + self::$max_pages) * self::$max_finds) + self::$max_finds;

        if(array_search($direction, ['DESC', 'ASC']) === FALSE)
            throw new InvalidArgumentException("Invalid direction.");

        if(array_search($sort, ['bought', 'price', 'id_prod']) === FALSE)
            throw new InvalidArgumentException("Invalid sort filter.");

        $query_params = self::formatValuesQuery($values);
        list($query_title, $query_text, $params_scan) = self::formatWordQuery($query, $category, $query_params);

        $result = FW::$DB->query("
            SELECT * FROM  $category
            WHERE (
                ($query_title)
                $query_params
            ) OR (
                ($query_text)
                $query_params
            )
            $params_scan
            ORDER BY $sort $direction
        ")->fetchAll();

        $important_part = Product::processProdParams(array_slice($result, 0, self::$max_finds), TRUE);

        return [
            'found' => count($important_part),
            'search_result' => $important_part,
            'pages_left' => ceil(count(array_slice($result, self::$max_finds)) / self::$max_finds)
        ];
    }

}
