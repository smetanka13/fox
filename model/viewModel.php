<?php

class View {

    public static $head = [
        'title' => NAME,
        'description' => '',
        'keywords' => ''
    ];

    public static function index() {
        //self::$head['title'] = 'Hello world!';
    }
    public static function product() {

        require_once 'model/productModel.php';
    	require_once 'model/searchModel.php';

        if(empty($_GET['category']) || empty($_GET['id']))
            header('Location: ' . URL . '?msg=Данная страница не найдена.');

        $prod = Product::getById($_GET['category'], $_GET['id']);

        $rand_spec = array_rand($prod['params']);
        $rand_related_spec = [
            $rand_spec => $prod['params'][$rand_spec]
        ];
        $related_prods = json_encode(
    		Search::find(
    			0,
    			'',
    			$prod['category'],
    			$rand_related_spec,
    			'bought',
    			'ASC',
    			10
    		)['search_result'],
    		JSON_UNESCAPED_UNICODE
    	);

        self::$head['title'] = NAME . ' - ' . $prod['title'];

        return [
            'related' => $related_prods,
            'prod' => $prod
        ];
    }
}
