<?php

    require 'model/searchModel.php';

    /* ---- Setup values ---- */

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $srch = isset($_GET['srch']) ? $_GET['srch'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'Масла';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : NULL;
    $from = isset($_GET['from']) ? $_GET['from'] : NULL;

    if(isset($_GET['values'])) {
        $values = json_decode(base64_decode($_GET['values']), TRUE);
        $params = array_keys($values);
    } else {
        $values = [];
        $params = [];
    }


    $result = Search::find($srch, $category, $values, $sort, $from);
    $prods = $result['search_result'];
?>

<link rel="stylesheet" type="text/css" href="css/search.css">
<link rel="stylesheet" type="text/css" href="css/product_block.css">

<!-- <div class="top_space">
    <?php
        for($i = 0; isset($params[$i]) && isset($values[$params[$i]]); $i++) {

            $exp = explode('/', $values[$params[$i]]);
            for($j = 0; isset($exp[$j]); $j++) {

                $tmp = $values;

                $tmp[$params[$i]] = preg_replace('/'.$exp[$j].'|\/'.$exp[$j].'/', '', $tmp[$params[$i]]);
                $tmp[$params[$i]] = preg_replace('/^\/{1}/', '', $tmp[$params[$i]]);
                $tmp[$params[$i]] = preg_replace('/\/{1}$/', '', $tmp[$params[$i]]);

                if($tmp[$params[$i]] == '')
                    unset($tmp[$params[$i]]);

                if(!empty($tmp))
                    echo '
                        <a href="?srch='.$srch.'&category='.$category.'&values='.base64_encode(json_encode($tmp)).'">
                            <div id="selected_filters">
                                '.$params[$i].': '.$exp[$j].'
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 95.939 95.939" style="enable-background:new 0 0 95.939 95.939;" xml:space="preserve"><g>	<path d="M62.819,47.97l32.533-32.534c0.781-0.781,0.781-2.047,0-2.828L83.333,0.586C82.958,0.211,82.448,0,81.919,0   c-0.53,0-1.039,0.211-1.414,0.586L47.97,33.121L15.435,0.586c-0.75-0.75-2.078-0.75-2.828,0L0.587,12.608   c-0.781,0.781-0.781,2.047,0,2.828L33.121,47.97L0.587,80.504c-0.781,0.781-0.781,2.047,0,2.828l12.02,12.021   c0.375,0.375,0.884,0.586,1.414,0.586c0.53,0,1.039-0.211,1.414-0.586L47.97,62.818l32.535,32.535   c0.375,0.375,0.884,0.586,1.414,0.586c0.529,0,1.039-0.211,1.414-0.586l12.02-12.021c0.781-0.781,0.781-2.048,0-2.828L62.819,47.97   z"/></g></svg>
                            </div>
                        </a>
                    ';
                else
                    echo '
                        <a href="?srch='.$srch.'&category='.$category.'">
                            <div id="selected_filters">
                                '.$params[$i].': '.$exp[$j].'
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 95.939 95.939" style="enable-background:new 0 0 95.939 95.939;" xml:space="preserve"><g>	<path d="M62.819,47.97l32.533-32.534c0.781-0.781,0.781-2.047,0-2.828L83.333,0.586C82.958,0.211,82.448,0,81.919,0   c-0.53,0-1.039,0.211-1.414,0.586L47.97,33.121L15.435,0.586c-0.75-0.75-2.078-0.75-2.828,0L0.587,12.608   c-0.781,0.781-0.781,2.047,0,2.828L33.121,47.97L0.587,80.504c-0.781,0.781-0.781,2.047,0,2.828l12.02,12.021   c0.375,0.375,0.884,0.586,1.414,0.586c0.53,0,1.039-0.211,1.414-0.586L47.97,62.818l32.535,32.535   c0.375,0.375,0.884,0.586,1.414,0.586c0.529,0,1.039-0.211,1.414-0.586l12.02-12.021c0.781-0.781,0.781-2.048,0-2.828L62.819,47.97   z"/></g></svg>
                            </div>
                        </a>
                    ';
            }
        }
    ?>
</div>
<div id="sleft_bar">

    <div class="select_bar filter_select">
        <h3 class="select_name info_block_p " style="margin: 10px">КАТЕГОРИИ</h3>
        <select class="select_points">
            <?php
                $list_categories = Category::getCategories();
                $list_params = NULL;
                $list_values = NULL;
                for($i = 0; isset($list_categories[$i]); $i++) {
                    $selected = "";

                    if($list_categories[$i] == $category)
                        $selected = "selected";

                    echo "<option $selected>".$list_categories[$i]."</option>";
                }
            ?>
        </select>
    </div>
    <div class="search_var">
        <?php
            $list_params = Category::getParams($category);

            for($j = 0; isset($list_params[$j]); $j++) {
                echo '
                    <h3 class="search_types">'.$list_params[$j].'<img id="timg'.$j.'" src="images/icons/up_arrow.svg" onclick="openCat('.$j.')"></h3>
                    <ul class="cat_types info_block_p" id="stype'.$j.'">
                ';
                $list_values = Category::getValues($category, $list_params[$j]);
                for($z = 0; isset($list_values[$z]); $z++) {
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 533.333 533.333" style="enable-background:new 0 0 533.333 533.333;" xml:space="preserve"><g><path d="M516.667,200H333.333V16.667C333.333,7.462,325.871,0,316.667,0h-100C207.462,0,200,7.462,200,16.667V200H16.667   C7.462,200,0,207.462,0,216.667v100c0,9.204,7.462,16.666,16.667,16.666H200v183.334c0,9.204,7.462,16.666,16.667,16.666h100   c9.204,0,16.667-7.462,16.667-16.666V333.333h183.333c9.204,0,16.667-7.462,16.667-16.666v-100   C533.333,207.462,525.871,200,516.667,200z"/></g></svg>';
                    $tmp = $values;
                    if(isset($tmp[$list_params[$j]])) {
                        $exp_arr = explode('/', $tmp[$list_params[$j]]);
                        if(!Main::lookSame($exp_arr, $list_values[$z])) {
                            $tmp[$list_params[$j]] .= "/".$list_values[$z];
                        } else {
                            $tmp[$list_params[$j]] = preg_replace('/'.$list_values[$z].'|\/'.$list_values[$z].'/', '', $tmp[$list_params[$j]]);
                            $tmp[$list_params[$j]] = preg_replace('/^\/{1}/', '', $tmp[$list_params[$j]]);
                            $tmp[$list_params[$j]] = preg_replace('/\/{1}$/', '', $tmp[$list_params[$j]]);

                            if($tmp[$list_params[$j]] == '')
                                unset($tmp[$list_params[$j]]);

                            $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 95.939 95.939" style="enable-background:new 0 0 95.939 95.939;" xml:space="preserve"><g>	<path d="M62.819,47.97l32.533-32.534c0.781-0.781,0.781-2.047,0-2.828L83.333,0.586C82.958,0.211,82.448,0,81.919,0   c-0.53,0-1.039,0.211-1.414,0.586L47.97,33.121L15.435,0.586c-0.75-0.75-2.078-0.75-2.828,0L0.587,12.608   c-0.781,0.781-0.781,2.047,0,2.828L33.121,47.97L0.587,80.504c-0.781,0.781-0.781,2.047,0,2.828l12.02,12.021   c0.375,0.375,0.884,0.586,1.414,0.586c0.53,0,1.039-0.211,1.414-0.586L47.97,62.818l32.535,32.535   c0.375,0.375,0.884,0.586,1.414,0.586c0.529,0,1.039-0.211,1.414-0.586l12.02-12.021c0.781-0.781,0.781-2.048,0-2.828L62.819,47.97   z"/></g></svg>';
                        }
                    } else {
                        $tmp[$list_params[$j]] = $list_values[$z];
                    }
                    if(!empty($tmp))
                        echo '<a href="?srch='.$srch.'&category='.$category."&values=".base64_encode(json_encode($tmp)).'&lastclick='.$list_params[$j].'"><li>'.$svg.$list_values[$z].'</li></a>';
                    else
                        echo '<a href="?srch='.$srch.'&category='.$category.'&lastclick='.$list_params[$j].'"><li>'.$svg.$list_values[$z].'</li></a>';

                }
                echo "</ul>";
            }
        ?>
    </div>
</div> -->


<div class="sch_cnt">
    <div class="sprod_desk_bl">
        <?php
            foreach ($prods as $prod) {
                $img = $prod['image'];
        ?>
            <div class="prods_cnt" style="margin-bottom: 50px;">
                <div class="prods_wrapper">
                    <h3 class="title"><?= $prod['title'] ?></h3>
                    <div class="prods_img_cnt"><img src="<?= "catalog/$category/$img" ?>"></div>
                    <p>
                    <?php
                        for($j = 0; isset($list_params[$j]); $j++) {
                            if(empty($prod[$list_params[$j]])) continue;
                            echo $list_params[$j].': '.$prod[$list_params[$j]].'</br>';
                        }
                    ?>
                    </p>
                    <div class="prods_bottom">
                        <a href="<?= 'product?category='.$prod['category'].'&id='.$prod['id'] ?>"><button>КУПИТЬ</button></a>
                        <h4><?= $prod['price'] ?> грн.</h4>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function openCat(num) {
        var type = $("#stype"+num);
        var img = $("#timg"+num);

        if(type.css("display") == "block") {
            type.slideUp(300);
            img.css("transform","scale(1,-1)");
        } else {
            type.slideDown(300),
            img.css("transform","scale(-1,1)");
        }
    }
</script>
