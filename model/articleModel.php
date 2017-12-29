<?php

class Article {

    public static $image_path = 'articles';

    public static function setup() {
        // TODO: CREATE DB WITH THAT FUNC
    }

    public static function upload($title, $text, $imgs) {

        self::checkInput($title, $text, $imgs);

        Main::query("
            INSERT INTO article (
                title, text, date
            ) VALUES (
                '$title', '$text', '".TIME."'
            )
        ");

        $id_article = Main::select("
            SELECT id_article FROM article
            WHERE title = '$title'
            LIMIT 1
        ")['id_article'];

        $query = '';
        foreach($imgs as $index => $img) {

            $imgs[$index]['name'] = substr(sha1($img['name'].TIME), 0, 15).'.'.Main::getFileExt($img['name']);

            if($index > 0) $query .= ',';
            $query .= "('$id_article', '{$imgs[$index]['name']}')";
        }

        try {
            Main::query("
                INSERT INTO article_image (
                    id_article, img
                ) VALUES $query
            ");
        } catch (RuntimeException $e) {
            Main::query("
                DELETE FROM article
                WHERE title = '$title'
                LIMIT 1
            ");
            throw new RuntimeException($e->getMessage());
        }

        try {

            self::uploadImages($imgs);

        } catch (RuntimeException $e) {
            Main::query("
                DELETE FROM article
                WHERE title = '$title'
                LIMIT 1
            ");
            Main::query("
                DELETE FROM article_image
                WHERE id_article = '$id_article'
            ");
            throw new RuntimeException($e->getMessage());
        }
    }
    private static function checkInput($title, $text, $imgs) {
        if(empty($title))
            throw new InvalidArgumentException("Заполните название.");
        if(empty($text))
            throw new InvalidArgumentException("Заполните текст.");
        if(count($imgs) > 4)
            throw new InvalidArgumentException("Не более 4-х изображений.");
    }
    private static function uploadImages($imgs) {
        foreach($imgs as $img) {
            if(!move_uploaded_file($img['tmp_name'], self::$image_path . '/' . $img['name'])) {
                foreach($imgs as $img) {
                    $file_path = self::$image_path . '/' . $img['name'];
                    if(file_exists($file_path)) unlink($file_path);
                }

                throw new RuntimeException("Ошибка загрузки файлов.");
            }
        }
    }
    public static function getAll() {
        $articles = Main::select("
            SELECT *
            FROM article
            JOIN article_image
            ON article_image.id_article = article.id_article
            LIMIT 1
        ", TRUE);

        foreach($articles as $i => $article) {
            $articles[$i]['img'] = self::$image_path . '/' . $article['img'];
        }

        return $articles;
    }
    public static function get($id_article) {

        $article = Main::select("
            SELECT *
            FROM article
            WHERE id_article = '$id_article'
            LIMIT 1
        ");

        $imgs = Main::select("
            SELECT *
            FROM article_image
            WHERE id_article = '$id_article'
        ", TRUE);

        foreach($imgs as $i => $img) {
            $article['images'][$i] = self::$image_path . '/' . $img['img'];
        }

        return $article;
    }
    public static function update($id_article, $title, $text, $imgs) {
        self::checkInput($title, $text, $imgs);

        Main::query("
            UPDATE article
            SET title = '$title', text = '$text'
            WHERE id_article = '$id_article'
            LIMIT 1
        ");

        if(empty($imgs)) return;

        $query = '';
        foreach($imgs as $index => $img) {

            $imgs[$index]['name'] = substr(sha1($img['name'].TIME), 0, 15).'.'.Main::getFileExt($img['name']);

            if($index > 0) $query .= ',';
            $query .= "('$id_article', '{$imgs[$index]['name']}')";
        }

        self::deleteImages($id_article);

        Main::query("
            INSERT INTO article_image (
                id_article, img
            ) VALUES $query
        ");

        try {

            self::uploadImages($imgs);

        } catch (RuntimeException $e) {
            Main::query("
                DELETE FROM article
                WHERE title = '$title'
                LIMIT 1
            ");
            Main::query("
                DELETE FROM article_image
                WHERE id_article = '$id_article'
            ");
            throw new RuntimeException($e->getMessage());
        }

    }
    public static function deleteImages($id_article) {

        $imgs = Main::select("
            SELECT * FROM article_image
            WHERE id_article = '$id_article'
        ", TRUE);

        foreach($imgs as $img) {
            $file_path = 'articles/' . $img['img'];
            if(file_exists($file_path)) unlink($file_path);
        }
    }
    public static function delete($id_article) {

        self::deleteImages($id_article);

        Main::query("
            DELETE FROM article
            WHERE id_article = '$id_article'
            LIMIT 1
        ");

    }
}
