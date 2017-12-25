<?php

require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

class Search {

    private static $max_finds = 12;
    private static $max_pages = 2;

    public static function find($srch, $category, $values = NULL, $sort = NULL, $from = NULL) {

        # ---- Строки для базы данных ---- #

        $query_sort = '';
        $query_srch_title = '';
        $query_srch_text = '';
        $query_params = '';

        # ---- Начальные параметры ---- #

        if(empty($page)) $page = 0;
        if(empty($sort_by)) $sort_by = 'bought';
        if(empty($direction)) $direction = 'ASC';

        $from = ($page * self::$max_finds);
        $to = (($page + self::$max_pages) * self::$max_finds) + self::$max_finds;

        if(!Main::lookSame(['DESC', 'ASC'], $direction))
            throw new InvalidArgumentException("Invalid direction.");

        if(!Main::lookSame(['bought', 'price', 'id_prod'], $sort_by))
            throw new InvalidArgumentException("Invalid sort filter.");

        # ---- Формирование запроса по спецификациям ---- #

        if(!empty($values)) {
            $params = array_keys($values);

            for($i = 0; isset($params[$i]); $i++) {
                $query_params .= " AND (";
                $tmp = explode('/', $values[$params[$i]]);
                for($j = 0; isset($tmp[$j]); $j++) {
                    if($j > 0)
                        $query_params .= " OR `".$params[$i]."` REGEXP '^".$tmp[$j]."$|^".$tmp[$j]."/|/".$tmp[$j]."$|/".$tmp[$j]."/' ";
                    else
                        $query_params .= " `".$params[$i]."` REGEXP '^".$tmp[$j]."$|^".$tmp[$j]."/|/".$tmp[$j]."$|/".$tmp[$j]."/' ";
                }
                $query_params .= ")";
            }
        } else {
            $values = [];
        }

        # ---- Формирование запроса по поисковым словам ---- #
        $words = explode(" ", $srch);
        foreach($words as $index => $word) {
            if($i > 0) {
                $query_srch_title .= " AND ";
                $query_srch_text .= " AND ";
            }
            $query_srch_title .= " `title` LIKE '%$word%' ";
            $query_srch_text .= " `text` LIKE '%$word%' ";
        }

        $list_params = Category::getParams($category);
        $params_scan = "";

        if($query_srch_title == "")
            $query_srch_title = " `title` LIKE '%%' ";

        if($query_srch_text == "")
            $query_srch_text = " `text` LIKE '%%' ";

        foreach($list_params as $i => $param) {
            $tmp = "";
            foreach($words as $j => $word) {
                if($j > 0) $tmp .= ' AND ';
                $tmp .= " `$param` LIKE '%$word%' ";
            }

            if(empty($tmp)) break;

            $params_scan .= "
                OR (
                    ($tmp)
                    $query_params
                )
            ";
        }

        $result = Main::select("
            SELECT * FROM  `$category`
            WHERE (
                ($query_srch_title)
                $query_params
            ) OR (
                ($query_srch_text)
                $query_params
            )
            $params_scan
            $query_sort
        ", TRUE);

        $important_part = Product::processProdParams(array_slice($result, 0, self::$max_finds), TRUE);

        return [
            'found' => count($important_part),
            'search_result' => $important_part,
            'pages_left' => ceil(count(array_slice($result, self::$max_finds)) / self::$max_finds)
        ];
    }
}
