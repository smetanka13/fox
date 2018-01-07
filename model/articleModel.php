<?php

class Article {

    public static $image_path = 'articles';

    public static function setup() {
        // TODO: CREATE DB WITH THAT FUNC
    }

    public static function upload($title, $text, $imgs) {

        self::checkInput($title, $text, $imgs);

        FW::$DB->action(function() {

            FW::$DB->insert('article', [
                'title' => $title,
                'text' => $text,
                'date' => TIME
            ]);

            $id_article = FW::$DB->id();

            $query = [];
            foreach($imgs as $img) {

                $imgs[$index]['name'] = substr(sha1($img['name'].TIME), 0, 15).'.'.Main::getFileExt($img['name']);

                $query[] = [
                    'id_article' => $id_article,
                    'img' => $imgs[$index]['name']
                ];
            }

            FW::$DB->insert('article_image', [
                'id_article',
                'img'
            ], $query);

            self::uploadImages($imgs);
        });
    }
    private static function checkInput($title, $text, $imgs) {

        if(mb_strlen($title) > 128)
            throw new InvalidArgumentException("Заголовок может иметь не более 128 символов.");
        if(FW::$DB->get('article', 'title', [
            'title' => $title
        ]))
            throw new InvalidArgumentException("Такой заголовок уже занят.");
        if(empty($title))
            throw new InvalidArgumentException("Заполните заголовок.");
        if(empty($text))
            throw new InvalidArgumentException("Заполните текст.");
        if(count($imgs) > 4)
            throw new InvalidArgumentException("Не более 4-х изображений.");
        if(count($imgs) < 1)
            throw new InvalidArgumentException("Не менее 1-го изображения.");
    }
    private static function uploadImages($imgs) {
        foreach($imgs as $img) {
            if(!move_uploaded_file($img['tmp_name'], 'material/' . self::$image_path . '/' . $img['name'])) {
                foreach($imgs as $img) {
                    $file_path = 'material/' . self::$image_path . '/' . $img['name'];
                    if(file_exists($file_path)) unlink($file_path);
                }

                throw new RuntimeException("Ошибка загрузки файлов.");
            }
        }
    }
    public static function getAll() {

        $articles = FW::$DB->select('article', '*');

        foreach($articles as $i => $article) {
            $img = FW::$DB->get('article_image', 'img', [
                'id_article' => $article['id_article']
            ]);
            $articles[$i]['img'] = 'material/' . self::$image_path . '/' . $img;
        }

        return $articles;
    }
    public static function get($id_article) {

        $article = FW::$DB->get('article', '*', [
            'id_article' => $id_article
        ]);

        $imgs = FW::$DB->select('article_image', '*', [
            'id_article' => $id_article
        ]);

        foreach($imgs as $i => $img) {
            $article['images'][$i] = 'material/' . self::$image_path . '/' . $img['img'];
        }

        return $article;
    }
    public static function update($id_article, $title, $text, $imgs) {
        self::checkInput($title, $text, $imgs);

        FW::$DB->action(function() {
            
            FW::$DB->update('update', [
                'title' => $title,
                'text' => $text
            ], [
                'id_article' => $id_article
            ]);

            if(empty($imgs)) return;

            $query = [];
            foreach($imgs as $img) {

                $imgs[$index]['name'] = substr(sha1($img['name'].TIME), 0, 15).'.'.Main::getFileExt($img['name']);

                $query[] = [
                    'id_article' => $id_article,
                    'img' => $imgs[$index]['name']
                ];
            }

            self::deleteImages($id_article);

            FW::$DB->insert('article_image', [
                'id_article',
                'img'
            ], $query);

            self::uploadImages($imgs);
        });
    }

    public static function deleteImages($id_article) {

        $imgs = FW::$DB->select('article_image', '*', [
            'id_article' => $id_article
        ]);

        foreach($imgs as $img) {
            $file_path = 'material/' . self::$image_path . '/' . $img['img'];
            if(file_exists($file_path)) unlink($file_path);
        }
    }
    public static function delete($id_article) {

        self::deleteImages($id_article);

        FW::$DB->delete('article', [
            'id_article' => $id_article
        ]);
        FW::$DB->delete('article_image', [
            'id_article' => $id_article
        ]);
    }
}
