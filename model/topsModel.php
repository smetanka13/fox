<?php

require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

class Tops {

    static public function getLastHotUpdate() {
        return FW::$DB->get('variable', 'data', [
            'name' => 'last_top_update'
        ]);
    }
    static public function updateHotProds() {

        if(TIME + 5184000 < self::getLastHotUpdate()) return;

        $categories = Category::getCategories();

        if(count($categories) == 1) {
            $prods = FW::$DB->select($categories[0], [
                'id_prod',
                'bought',
                'category'
            ], [
                'ORDER' => 'bought',
                'LIMIT' => 10
            ]);
        } else {
            $query = '';
            foreach($categories as $i => $category) {
                if($i > 0) $query .= " UNION ";
                $query .= "
                    (
                        SELECT id_prod, bought, category FROM $category
                        ORDER BY bought
                        LIMIT 10
                    )
                ";
            }
            $prods = FW::$DB->query("
                $query
                ORDER BY bought
                LIMIT 10
            ")->fetchAll();
        }

        foreach($prods as $index => $prod) {
            unset($prods[$index]['bought']);
        }

        FW::$DB->update('variable', [
            'data' => json_encode($prods, JSON_UNESCAPED_UNICODE)
        ], [
            'name' => 'hot_prods'
        ]);

        FW::$DB->update('variable', [
            'data' => TIME
        ], [
            'name' => 'last_hot_update'
        ]);
    }
    static public function getHotProds() {
        $prods = FW::$DB->get('variable', 'data', [
            'name' => 'hot_prods'
        ]);

        return Product::selectFromDiffCategories(json_decode($prods, TRUE));
    }
    static public function getNewProds() {
        $prods = FW::$DB->get('variable', 'data', [
            'name' => 'new_prods'
        ]);

        return Product::selectFromDiffCategories(json_decode($prods, TRUE));
    }
    static public function addNewProds($id_prod, $category) {
        $new_prods = FW::$DB->get('variable', 'data', [
            'name' => 'new_prods'
        ]);

        if(!empty($new_prods))
            $new_prods = json_decode($new_prods, TRUE);
        else
            $new_prods = [];

        array_unshift($new_prods, [
            'id_prod' => $id_prod,
            'category' => $category
        ]);

        if(count($new_prods) == 11) unset($new_prods[10]);

        $new_prods = json_encode($new_prods, JSON_UNESCAPED_UNICODE);
        $new_prods = FW::$DB->update('variable', [
            'data' => $new_prods
        ], [
            'name' => 'new_prods'
        ]);
    }
}
