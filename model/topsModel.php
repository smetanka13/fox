<?php

require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

class Tops {

    static public function getLastTopUpdate() {
        return Main::select("
            SELECT `data` FROM `variable`
            WHERE `name` = 'last_top_update'
            LIMIT 0, 1
        ")['data'];
    }
    static public function updateTopProds() {

        if(TIME + 5184000 < self::getLastTopUpdate()) return;

        $categories = Category::getCategories();
        $query = '';
        for($i = 0; isset($categories[$i]); $i++) {
            if($i > 0)
                $query .= "
                    UNION (SELECT `id`, `bought`, `category` FROM `".$categories[$i]."`
                    ORDER BY `bought`
                    LIMIT 0, 10)
                ";
            else
                $query .= "
                    (SELECT `id`, `bought`, `category` FROM `".$categories[$i]."`
                    ORDER BY `bought`
                    LIMIT 0, 10)
                ";
        }

        if(count($categories) == 1) {
            $query = "
                SELECT `id`, `bought`, `category` FROM `".$categories[0]."`
            ";
        }

        $prods = Main::select("
            $query
            ORDER BY `bought`
            LIMIT 0, 10
        ", TRUE);

        foreach($prods as $index => $prod) {
            unset($prods[$index]['bought']);
        }

        Main::query("
            UPDATE `variable`
            SET `data` = '".json_encode($prods, JSON_UNESCAPED_UNICODE)."'
            WHERE `name` = 'top_prods'
        ");
        Main::query("
            UPDATE `variable`
            SET `data` = '".TIME."'
            WHERE `name` = 'last_top_update'
        ");

    }
    static public function getTopProds() {
        $prods = Main::select("
            SELECT `data` FROM `variable`
            WHERE `name` = 'top_prods'
            LIMIT 0, 1
        ")['data'];
        return Product::selectFromDiffCategories(json_decode($prods, TRUE));
    }
    static public function getNewProds() {
        $prods = Main::select("
            SELECT `data` FROM `variable`
            WHERE `name` = 'new_prods'
            LIMIT 0, 1
        ")['data'];
        return Product::selectFromDiffCategories(json_decode($prods, TRUE));
    }
    static public function addNewProds($id, $category) {
        $new_prods = Main::select("
            SELECT `data` FROM `variable`
            WHERE `name` = 'new_prods'
            LIMIT 1
        ")['data'];

        if(!empty($new_prods))
            $new_prods = json_decode($new_prods, TRUE);
        else
            $new_prods = [];

        array_unshift($new_prods, [
            'id' => $id,
            'category' => $category
        ]);

        if(count($new_prods) == 11) unset($new_prods[10]);

        $new_prods = json_encode($new_prods, JSON_UNESCAPED_UNICODE);
        Main::query("
            UPDATE `variable`
            SET `data` = '$new_prods'
            WHERE `name` = 'new_prods'
            LIMIT 1
        ");
    }
}
