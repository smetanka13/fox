<?php

class View {

    public static $head = [
        'title' => NAME,
        'description' => DESCRIPTION,
        'keywords' => KEYWORDS
    ];

    public static function index() {
        self::$head['title'] = NAME . ' - ' . 'интернет-магазин автозапчастей';
    }

    public static function blog() {
        self::$head['title'] = NAME . ' - ' . 'новости';
    }

    public static function cart() {
        self::$head['title'] = NAME . ' - ' . 'корзина';
    }

    public static function delivery() {
        self::$head['title'] = NAME . ' - ' . 'доставка';
    }

    public static function contacts() {
        self::$head['title'] = NAME . ' - ' . 'контакты';
    }

    public static function guarantee() {
        self::$head['title'] = NAME . ' - ' . 'гарантия';
    }

    public static function callback() {
        self::$head['title'] = NAME . ' - ' . 'отзывы';
    }

    public static function privacy() {
        self::$head['title'] = NAME . ' - ' . 'политика конфиденциальности';
    }

    public static function personal() {
        self::$head['title'] = NAME . ' - ' . 'личный кабинет';
    }

    public static function product() {

        require_once 'model/productModel.php';
    	require_once 'model/searchModel.php';

        if(empty($_GET['category']) || empty($_GET['id']))
            header('Location: ' . URL . '?msg=Данный товар не найден.');

        $prod = Product::getById($_GET['category'], $_GET['id']);

        if(empty($prod))
            header('Location: ' . URL . '?msg=Данный товар не найден.');

        $rand_spec = array_rand($prod['params']);
        $rand_related_spec = [
            $rand_spec => $prod['params'][$rand_spec]
        ];
        $related_prods = Search::find(
            0,
            '',
            $prod['category'],
            $rand_related_spec,
            'bought',
            'ASC',
            10
        )['search_result'];

        foreach ($related_prods as $i => $rel_prod) {
            if($rel_prod['id_prod'] == $_GET['id']) {
                unset($related_prods[$i]);
                break;
            }
        }

        self::$head['title'] = NAME . ' - ' . $prod['title'];

        return [
            'related' => json_encode($related_prods, JSON_UNESCAPED_UNICODE),
            'prod' => $prod
        ];
    }



    public static function search() {

        require 'model/searchModel.php';

        $page = 0;
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'bought';
        $direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

        // если ...
        if(isset($_GET['category']) && Category::checkCategory($_GET['category'], TRUE)) {

            // указана категория , то ищем по ней
            $category = $_GET['category'];

            if(isset($_GET['settings'])) {
                $_GET['settings'] = str_replace(' ', '+', $_GET['settings']);
                $settings = json_decode(
                    base64_decode($_GET['settings']),
                    TRUE
                );
            } else {
                $settings = [];
            }

        } else {

            // НЕ указана категория , то ищем категорию , которая
            // больше всего походит на нужную
            $category = Search::findCategory($query);

            $settings = [];

        }

        // если таки не нашло ничего - мы наугад из всех категорий выбираем одну
        if(empty($category))
            $category = Category::getCategories()[rand(0, count(Category::getCategories()) - 1)];

        $result = Search::find($page, $query, $category, $settings, $sort, $direction);

        $result['found'] = $result['pages_left'] * $result['found'];

        self::$head['title'] = NAME . ' - ' . "поиск в категории '$category'";

        return [
            'prods' => $result['search_result'],
            'pages_left' => $result['pages_left'],
            'found' => $result['found'],
            'page' => $page,
            'query' => $query,
            'category' => $category,
            'sort' => $sort,
            'direction' => $direction,
            'settings' => $settings
        ];
    }
}
