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
        'discount',
        'discount_percent',
        'params'
    ];

    public static function add($articule, $title, $price, $category, $spec = []) {

        FW::$DB->get('category_params', 'quantity', [
            'category' => $category
        ]);

        if(empty($quantity))
            throw new InvalidArgumentException("No category $category found.");

        $quantity++;

        FW::$DB->action(function() {
            FW::$DB->update('category_params', [
                'quantity' => $quantity
            ], [
                'category' => $category
            ]);

            $query_array = [
                'articule' => $articule,
                'title' => $title,
                'price' => $price,
                'category' => $category
            ];
            foreach($spec as $param => $value) {
                $query_array[$param] = $value;
            }

            FW::$DB->insert($category, $query_array);

            Tops::addNewProds(
                FW::$DB->get($category, 'id_prod', [
                    'title' => $title
                ]),
            $category);
        });
    }
    public static function update($id_prod, $articule, $title, $price, $category, $spec = []) {

        if(!is_numeric($price))
            throw new InvalidArgumentException("Цена должна быть цифровым значением.");

        Category::checkCategory($category);

        $query = [
            'title' => $title,
            'price' => $price,
            'category' => $category
        ];
        foreach($spec as $param => $value) {
            Category::checkParam($category, $param);
            $query[$param] = $value;
        }

        if(empty($articule)) $query['articule'] = $articule;

        FW::$DB->update($category, $query, [
            'id_prod' => $id_prod
        ]);

    }

    public static function humanUpdate($id_prod, $price, $category, $text, $spec = []) {

        if(!is_numeric($price))
            throw new InvalidArgumentException("Цена должна быть цифровым значением.");

        Category::checkCategory($category);

        $text = str_replace("\n", '<br>', $text);

        $query = [
            'price' => $price,
            'category' => $category,
            'text' => $text
        ];
        foreach($spec as $param => $value) {
            Category::checkParam($category, $param);
            $query[$param] = $value;
        }

        FW::$DB->update($category, $query, [
            'id_prod' => $id_prod
        ]);

    }

    public static function selectFromDiffCategories($prods) {

        if(empty($prods)) return;

        if(count($prods) == 1) {

            $result = self::getById($prods[0]['category'], [$prods[0]['id_prod']]);

        } else {

            $query = '';
            $category = Category::getCategories();

            foreach($prods as $i => $prod) {
                if(array_search($prod['category'], $category) === FALSE || !is_numeric($prod['id_prod']))
                    continue;
                if($i != 0) $query .= ' UNION ';
                $query .= "(
                    SELECT * FROM {$prod['category']}
                    WHERE id_prod = {$prod['id_prod']}
                    LIMIT 1
                )";
            }

            $result = self::processProdParams(FW::$DB->query($query)->fetchAll(), TRUE);
        }

        return $result;
    }

    public static function processProdParams($input, $array = FALSE) {

        if($array) {

            foreach($input as $i => $prod) {
                $input[$i]['params'] = [];
                foreach($prod as $key => $value) {
                    if(array_search($key, self::$default_values) === FALSE) {
                        $input[$i]['params'][$key] = $value;
                        unset($input[$i][$key]);
                    }
                }
            }

        } else {

            $input['params'] = [];
            foreach($input as $key => $value) {
                if(array_search($key, self::$default_values) === FALSE) {
                    $input['params'][$key] = $value;
                    unset($input[$key]);
                }
            }

        }

        return $input;
    }

    public static function upload($category, $id_prod, $text, $price, $quantity, $params, $img) {

        Category::checkCategory($category);

        Product::humanUpdate($id_prod, $price, $category, $text, $params);

        $img = $img[0];

        # --- IF IMAGE EXISTS, UPLOAD IT --- #
        if(empty($img)) return;

        $img_dir = './material/catalog/'.$category;

        $prev_image = FW::$DB->get($category, 'image', [
            'id_prod' => $id_prod
        ]);

        $valid_ext = ['jpeg', 'png', 'jpg', 'bmp'];

        $extention = FW::getFileExt($img['name']);

        if(array_search($valid_ext, $extention) === FALSE)
            throw new InvalidArgumentException("Недопустимое расширение для файла '$extention'.");

        $image = substr(sha1(TIME . $img['name']), 0, (31 - strlen($extention))) . ".$extention";

        $full_path = $img_dir . '/' . $image;

        if(!empty($prev_image) && file_exists($img_dir . '/' . $prev_image))
            unlink($img_dir . '/' . $prev_image);

        if(!move_uploaded_file($img['tmp_name'], $full_path))
            throw new RuntimeException("Не вышло загрузить фото.");

        try {
            FW::$DB->update($category, [
                'image' => $image
            ], [
                'id_prod' => $id_prod
            ]);
        } catch (Exception $e) {
            unlink($full_path);
            throw $e;
        }

    }
    public static function getApprox($category, $str) {

        Category::checkCategory($category);

        if(empty($str)) return;

        require_once 'model/searchModel.php';

        if($str[0] == ':') {

            $prods = FW::$DB->get($category, '*', [
                'articule' => substr($str, 1)
            ]);

            if(empty($prods))
                return;
            else
                return self::processProdParams($prods);
        } else {
            return Search::find(0, $str, $category)['search_result'][0];
        }
    }
    public static function getFullPriceCookie($cookie) {

        $cookie = array_values(json_decode($cookie, JSON_UNESCAPED_UNICODE));

        $prods = self::selectFromDiffCategories($cookie);

        $price = 0;
        foreach($prods as $index => $prod) {
            $price += ($prod['price'] * (1 - ($prod['discount_percent'] / 100))) * $cookie[$index]['quantity'];
        }
        return $price;
    }
    public static function getById($category, $id_prod) {

        Category::checkCategory($category);

        if(is_array($id_prod)) {

            $result = FW::$DB->select($category, '*', [
                'id_prod' => $id_prod
            ]);

        } else {

            $result = FW::$DB->get($category, '*', [
                'id_prod' => $id_prod
            ]);

        }

        return self::processProdParams($result, is_array($id_prod));
    }
    public static function setDiscount($category, $id_prod, $percent) {

        Category::checkCategory($category);

        FW::$DB->update($category, [
            'discount' => 1,
            'discount_percent' => $percent
        ], [
            'id_prod' => $id_prod
        ]);
    }
    public static function offDiscount($category, $id_prod) {
        Category::checkCategory($category);

        FW::$DB->update($category, [
            'discount' => 0,
            'discount_percent' => 0
        ], [
            'id_prod' => $id_prod
        ]);
    }
}
