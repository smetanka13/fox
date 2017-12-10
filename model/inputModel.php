<?php

require_once 'model/currencyModel.php';
require_once 'model/categoryModel.php';
require_once 'model/productModel.php';
require_once 'model/topsModel.php';


class Input {
    public static function excelUpload($file) {

        require_once 'plugins/PHPExcel-1.8/Classes/PHPExcel.php';

        /* --- EXCEL STRUCTURE ---

            [A] - brand
            [B] - articula
            [C] - title
            [D] - subcategory
            [E] - price

            --- OIL ---

            [F] - mode
            [G] - amount

        */

        foreach($file as $excel) {
            $objPHPExcel = PHPExcel_IOFactory::load($excel['tmp_name']);
            $excel_array = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

            $categories = Category::getCategories();
            $category = str_replace('.xlsx', '', $excel['name']);

            if($category == 'Масла') {

                $spec_params = [
                    'Бренд' => 'A',
                    'Подкатегория' => 'D',
                    'Цена за упаковку/литр' => 'F',
                    'Литраж' => 'G'
                ];

            } else {
                $spec_params = [
                    'Бренд' => 'A',
                    'Подкатегория' => 'D'
                ];
            }

            # --- If no category exists - stop --- #
            if(!Main::lookSame($categories, $category))
                throw new InvalidArgumentException("Категория '$category' не найдена.");

            foreach($excel_array as $index => $row) {
                if($index == 1) continue;

                # --- Add new values to category params --- #
                foreach($spec_params as $param => $pos) {
                    Category::addValues($category, $param, $row[$pos], FALSE);
                }

                $tmp_params = $spec_params;
                foreach($spec_params as $param => $pos) {
                    $tmp_params[$param] = $row[$pos];
                }

                $id = Main::select("
                    SELECT `id` FROM `".$category."`
                    WHERE `title` = '".$row['C']."'
                    LIMIT 1
                ")['id']

                if($id) {
                    Product::update(
                        $id,
                        $row['B'],
                        $row['C'],
                        $row['E'],
                        $category,
                        $tmp_params
                    );
                } else {
                    Product::add(
                        $row['B'],
                        $row['C'],
                        $row['E'],
                        $category,
                        $tmp_params
                    );
                }
            }
        }
    }
}
