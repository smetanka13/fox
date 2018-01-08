<?php

require_once 'model/currencyModel.php';
require_once 'model/categoryModel.php';
require_once 'model/productModel.php';
require_once 'model/topsModel.php';


class Input {

    /**
     * Uploads product from a specified .xlsx (excel) file
     *
     * @param file - path to excel file
     */
    public static function excelUpload($file) {

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

            # if no category exists - stop
            Category::checkCategory($category, TRUE);

            foreach($excel_array as $index => $row) {
                if($index == 1) continue;

                # add new values to category params
                foreach($spec_params as $param => $pos) {
                    Category::addValues($category, $param, $row[$pos], FALSE);
                }

                $tmp_params = $spec_params;
                foreach($spec_params as $param => $pos) {
                    $tmp_params[$param] = $row[$pos];
                }

                $id_prod = FW::$DB->get($category, [
                    'id_prod'
                ], [
                    'title' => $row['C']
                ]);

                if($id_prod) {

                    # if product with that ID exists - rewrite it
                    Product::update(
                        $id_prod,
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
