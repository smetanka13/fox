<?php

require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

class Search {

    private static $max_finds = 12;
    private static $max_pages = 2;
    const NO_EXPLODE = 1;
    const STRICT_SEARCH = 2;

    public static function findCategory($str) {

        if(empty($str)) return FALSE;

        $words = explode(' ', $str);

        $query = [];
        foreach ($words as $i => $word) {
            $query['OR #'.$i] = [
                'category_param[~]' => $word,
                'values[~]' => $word
            ];
        }

        $result = FW::$DB->get('param_value', 'category_param', [
            'OR' => $query
        ]);

        return explode('/', $result)[0];
    }

    private static function formatWordQuery($query, $category, $query_params, $flag = NULL) {
        # ---- Формирование запроса по поисковым словам ---- #

        $query_title = '';
        $query_text = '';
        $quated_words = [];

        if($flag != self::NO_EXPLODE && $flag != self::STRICT_SEARCH)
            $words = explode(" ", $query);

        foreach($words as $i => $word) {
            if($i > 0) {
                $query_title .= ' AND ';
                $query_text .= ' AND ';
            }
            $quated_words[$i] = FW::$DB->quote("%$word%");
            $query_title .= "title LIKE {$quated_words[$i]}";
            $query_text .= "text LIKE {$quated_words[$i]}";
        }

        $list_params = Category::getParams($category);
        $params_scan = '';

        if($query_title == '')
            $query_title = "title LIKE '%%'";

        if($query_text == '')
            $query_text = "text LIKE '%%'";

        foreach($list_params as $index => $param) {
            $tmp = "";
            foreach($words as $i => $word) {
                if($i > 0) $tmp .= ' AND ';
                $tmp .= "`$param` LIKE {$quated_words[$i]}";
            }

            if(empty($tmp)) break;

            if($index > 0) $params_scan .= 'OR';

            $params_scan .= "
                (
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

    private static function formatValuesQuery($category, $values) {
        # ---- Формирование запроса по спецификациям ---- #

        $query = '';

        if(!empty($values)) {
            $params = array_keys($values);

            foreach($params as $param) {
                Category::checkParam($category, $param);
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

    public static function find($page, $query, $category, $values = NULL, $sort = NULL, $direction = NULL, $discount = FALSE, $finds = 12, $flag = NULL) {

        Category::checkCategory($category);

        if($finds > self::$max_finds) $finds = self::$max_finds;

        if(!is_numeric($page)) $page = 0;

        if(empty($page)) $page = 0;
        if(empty($sort)) $sort = 'bought';
        if(empty($direction)) $direction = 'ASC';

        $from = $page * $finds;
        $to = ($page + self::$max_pages) * $finds;

        if(array_search($direction, ['DESC', 'ASC']) === FALSE)
            throw new InvalidArgumentException("Invalid direction.");

        if(array_search($sort, ['bought', 'price', 'id_prod']) === FALSE)
            throw new InvalidArgumentException("Invalid sort filter.");

        $query = trim(preg_replace("/ +/", ' ',$query));

        $query_params = self::formatValuesQuery($category, $values);
        list($query_title, $query_text, $params_scan) = self::formatWordQuery($query, $category, $query_params, $flag);

        $result = FW::$DB->query("
            SELECT * FROM  $category
            WHERE (
                ($query_title)
                $query_params
            ) OR (
                ($query_text)
                $query_params
            ) OR (
                $params_scan
            )
            AND `discount` = '".intval($discount)."'
            ORDER BY $sort $direction
            LIMIT $from, $to
        ")->fetchAll();

        // echo("
        //     SELECT * FROM  $category
        //     WHERE (
        //         ($query_title)
        //         $query_params
        //     ) OR (
        //         ($query_text)
        //         $query_params
        //     ) OR (
        //         $params_scan
        //     )
        //     AND `discount` = '".intval($discount)."'
        //     ORDER BY $sort $direction
        //     LIMIT $from, $to
        // ");

        $important_part = Product::processProdParams(array_slice($result, 0, $finds), TRUE);

        return [
            'found' => count($important_part),
            'search_result' => $important_part,
            'pages_left' => ceil(count(array_slice($result, $finds)) / $finds)
        ];
    }

}
