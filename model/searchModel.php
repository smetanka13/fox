<?php

require_once 'model/categoryModel.php';

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

        if(!Main::lookSame(['bought', 'price', 'id'], $sort_by))
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
        $srch_string = explode(" ", $srch);
        for($i = 0; isset($srch_string[$i]) && $srch_string[$i] != NULL; $i++) {
            if($i > 0) {
                $query_srch_title .= " AND `title` LIKE '%".$srch_string[$i]."%' ";
                $query_srch_text .= " AND `text` LIKE '%".$srch_string[$i]."%' ";
            } else {
                $query_srch_title .= " `title` LIKE '%".$srch_string[$i]."%' ";
                $query_srch_text .= " `text` LIKE '%".$srch_string[$i]."%' ";
            }
        }

        $list_params = Category::getParams($category);
        $params_scan = "";

        if($query_srch_title == "")
            $query_srch_title = " `title` LIKE '%%' ";

        if($query_srch_text == "")
            $query_srch_text = " `text` LIKE '%%' ";

        for($i = 0; isset($list_params[$i]); $i++) {
            $tmp = "";
            $srch_string[0];
            for($j = 0; isset($srch_string[$j]) && $srch_string[$j] != NULL; $j++) {
                if($j > 0) {
                    $tmp .= " AND `".$list_params[$i]."` LIKE '%".$srch_string[$j]."%' ";
                } else {
                    $tmp .= " `".$list_params[$i]."` LIKE '%".$srch_string[$j]."%' ";
                }
            }

            if($tmp == "")
                break;

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

        $important_part = array_slice($result, 0, self::$max_finds);

        return [
            'found' => count($important_part),
            'search_result' => $important_part,
            'pages_left' => ceil(count(array_slice($result, self::$max_finds)) / self::$max_finds)
        ];
    }
}
