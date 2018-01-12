<?php

require_once 'model/currencyModel.php';
require_once 'model/categoryModel.php';
require_once 'model/productModel.php';

/* --- tovar.txt STRUCTURE ---

    [0] 1C CODE (8)
    [1] ARTICULUS (70)
    [2] BRAND (25)
    [3] ANALOGS (250) - separated by ";"
    [4] NAME (100)
    [5] OTHER (100) - models, that use this product
    [6] QUANTITY
    [7] PRICE
    [8] CATEGORY

*/

# --- Encoder from 1251 to UTF-8 --- #
function getContent(&$pointer) {
    return mb_convert_encoding(zip_entry_read($pointer, zip_entry_filesize($pointer)), 'UTF-8', 'Windows-1251');
}

$zip = zip_open("input/base_ftp.zip");

# --- Read and parse val.txt (currency file) --- #
# --- id|name|value\r\n --- #
# --- id|name|value --- #

$next = zip_read($zip);
$file = getContent($next);

$file = explode("\r\n", $file);
for($i = 0; isset($file[$i]); $i++) {
    $file[$i] = explode('|', $file[$i]);
}
Currency::update($file);

# --- Read and parse tovar.txt (products file) --- #
$next = zip_read($zip);
$file = getContent($next);

$tmp_str = "";
$j = 0;
for($i = 0; isset($file[$i]); $i++) {
    if($file[$i] == "\r") {
        $tmp_arr = explode('|', $tmp_str);
        $categories = Category::getCategories();

        if(empty($tmp_arr[8])) continue;

        # --- If no category exists , create new one --- #
        if(!Main::lookSame($categories, $tmp_arr[8])) {
            Category::newCategory($tmp_arr[8], NULL);
        }

        # --- Add new values to category params --- #
        if(!empty($tmp_arr[2])) {

            $values = Category::getValues($tmp_arr[8], 'Бренд');
            if(!empty($values)) {
                if(!Main::lookSame($values, $tmp_arr[2])) {
                    $values[] = $tmp_arr[2];
                    $values = implode(';', $values);
                    if(!$SQL->query("
                        UPDATE `param_values`
                        SET `values` = '$values'
                        WHERE `category_param` = '".$tmp_arr[8]."/Бренд'
                        LIMIT 1
                    ")) {
                        $JSON->write('param_values', 'Не удалось добавить значенение '.$tmp_arr[2].' в '.$tmp_arr[8].'/Бренд. (#1)');
                    }
                }
            } else {
                if(!$SQL->query("
                    INSERT INTO `param_values` (
                        `category_param`, `values`
                    ) VALUES (
                        '".$tmp_arr[8]."/Бренд', '".$tmp_arr[2]."'
                    )
                ")) {
                    $JSON->write('param_values', 'Не удалось добавить значенение '.$tmp_arr[2].' в '.$tmp_arr[8].'/Бренд. (#2)');
                }
            }
        }

        if($JSON->ok()) {
            if(Main::select("
                SELECT `id` FROM `".$tmp_arr[8]."`
                WHERE `id` = '".$tmp_arr[0]."'
                OR `title` = '".$tmp_arr[4]."'
                LIMIT 1
            ")) {
                Product::update(
                    $tmp_arr[2],
                    $tmp_arr[0],
                    $tmp_arr[1],
                    $tmp_arr[3],
                    $tmp_arr[4],
                    $tmp_arr[5],
                    $tmp_arr[6],
                    $tmp_arr[7],
                    $tmp_arr[8]
                );
            } else {
                Product::add(
                    $tmp_arr[2],
                    $tmp_arr[0],
                    $tmp_arr[1],
                    $tmp_arr[3],
                    $tmp_arr[4],
                    $tmp_arr[5],
                    $tmp_arr[6],
                    $tmp_arr[7],
                    $tmp_arr[8]
                );
            }
            $tmp_str = "";
            $j++;
            $i++;
            continue;
        } else {
            break;
        }
    }
    $tmp_str .= $file[$i];
}
