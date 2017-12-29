<?php

class Article {
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
            if(!move_uploaded_file($img['tmp_name'], 'articles/' . $img['name'])) {
                foreach($imgs as $img) {
                    $file_path = 'articles/' . $img['name'];
                    if(file_exists($file_path)) unlink($file_path);
                }

                throw new RuntimeException("Ошибка загрузки файлов.");
            }
        }
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



        foreach($imgs as $img) {
            $file_path = 'articles/' . $img['name'];
            if(file_exists($file_path)) unlink($file_path);
        }
    }
}
