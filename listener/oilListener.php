<?php

require_once 'model/searchModel.php';
require_once 'model/oilparserModel.php';

$to_skip = [
    'HONDA',
    'SUZUKI',
    'CITROEN',
    'DAEWOO',
    'DAIHATSU',
    'FIAT',
    'FORD'
];

$html = file_get_contents('https://oil.autoklad.ua/?sector=1');
$doc = new DOMDocument();
@$doc->loadHTML($html);
$marks = $doc->getElementsByTagName('a');
foreach($marks as $mark) {

    if(Main::lookSame($to_skip, $mark->nodeValue)) continue;

    $db_marks = Main::select("
        SELECT `data` FROM `variables`
        WHERE `name` = 'marks'
        LIMIT 1
    ");
    if(!empty($db_marks['data'])) {
        $db_marks = explode(';', $db_marks['data']);
    } else {
        $db_marks = [];
    }
    if(!Main::lookSame($db_marks, $mark->nodeValue)) {
        $db_marks[] = $mark->nodeValue;
        $SQL->query("
            UPDATE `variables`
            SET `data` = '".implode(';', $db_marks)."'
            WHERE `name` = 'marks'
            LIMIT 1
        ");
        $SQL->query("
            INSERT INTO `models` (
                `mark`
            ) VALUES (
                '".$mark->nodeValue."'
            )
        ");
    }
    $html_one = file_get_contents('https:'.$mark->attributes->item(0)->value);
    $doc_one = new DOMDocument();
    @$doc_one->loadHTML($html_one);
    $models = $doc_one->getElementsByTagName('a');
    foreach($models as $model) {

        $model->nodeValue = preg_replace('/\'/', '\\\'', $model->nodeValue);

        $db_models = Main::select("
            SELECT `models` FROM `models`
            WHERE `mark` = '".$mark->nodeValue."'
            LIMIT 1
        ");
        if(!empty($db_models['models'])) {
            $db_models = explode(';', $db_models['models']);
        } else {
            $db_models = [];
        }
        if(!Main::lookSame($db_models, $model->nodeValue)) {
            $db_models[] = $model->nodeValue;
            $SQL->query("
                UPDATE `models`
                SET `models` = '".implode(';', $db_models)."'
                WHERE `mark` = '".$mark->nodeValue."'
                LIMIT 1
            ");
            $SQL->query("
                INSERT INTO `oil_parser` (
                    `car`
                ) VALUES (
                    '".$mark->nodeValue."/".$model->nodeValue."'
                )
            ");
        }
        $html_two = file_get_contents('https:'.$model->attributes->item(0)->value);
        $doc_two = new DOMDocument();
        @$doc_two->loadHTML($html_two);
        $tables = $doc_two->getElementsByTagName('td');
        for($i = 0; !empty($table = $tables->item($i)); $i+=3) {

            # --- IF STRING IN TABLE HAS 'Двигатель' --- #
            if(preg_match('/Двигатель/', $table->nodeValue)) {
                $oils = $tables->item($i+2)->getElementsByTagName('a');
                foreach($oils as $oil) {

                    # --- DELETING ' - ' FROM NAME, WHERE '10W-40' --- #
                    $oil->nodeValue = preg_replace("/-/", '', $oil->nodeValue);

                    $oil_brands = Category::getValues('МАСЛА &', 'Бренд');
                    $values = ['Бренд' => ''];

                    # --- DETECTING AND DELETING BRAND FROM MAIN NAME --- #
                    foreach($oil_brands as $oil_brand) {
                        if(preg_match('/'.$oil_brand.'/', $oil->nodeValue)) {
                            $values['Бренд'] = $oil_brand;
                            $oil->nodeValue = preg_replace("/".$oil_brand." /", '', $oil->nodeValue);
                            break;
                        }
                    }

                    # --- SEARCH PARSED OIL NAME --- #
                    $finded_oils = Search::find($oil->nodeValue, 'МАСЛА &', $values, NULL, NULL);

                    #TODO ВЫГРУЗКУ В БД СДЕЛАЙ, УЩЕРБОК
                    if(!empty($finded_oils)) {

                        foreach($finded_oils as $finded_oil) {
                            $oils_id = OilParser::getOilsID($mark->nodeValue, $model->nodeValue);
                            if(!Main::lookSame($oils_id, $finded_oil['id'])) {
                                $oils_id[] = $finded_oil['id'];
                                $oils_id = implode(';', $oils_id);
                                $SQL->query("
                                    UPDATE `oil_parser`
                                    SET `oils_id` = '$oils_id'
                                    WHERE `car` = '".$mark->nodeValue."/".$model->nodeValue."'
                                    LIMIT 1
                                ");
                            }
                        }
                    }
                }
                break;
            }
        }
        #break;
    }
    #break;
}
