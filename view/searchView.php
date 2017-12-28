<?php

    require 'model/searchModel.php';

    /* ---- Setup values ---- */

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'Масла';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : NULL;
    $direction = isset($_GET['direction']) ? $_GET['direction'] : NULL;

    if(isset($_GET['settings'])) {
        $settings = json_decode(base64_decode($_GET['settings']), TRUE);
    } else {
        $settings = [];
    }

    $result = Search::find($page, $query, $category, $settings, $sort, $direction);
    $prods = $result['search_result'];
?>

<link rel="stylesheet" type="text/css" href="css/cart.css">
<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/search2.css">

<div class="reg_prod col-xs-12 col-sm-4 col-md-3 col-lg-3">
	<div class="reg_prod_cnt" id="menu_f">
        <!-- ЗДЕСЬ ВСТАВЛЯЕШЬ БЛОКИ ФИЛЬТРОВ -->
        <div class="catalog">
            <div class="cnt">
                <div class="list pbm">
                    <?php
                        $params = Category::getParams($category);
                        foreach($params as $i => $param) {
                            $values = Category::getValues($category, $param);
                    ?>
                    <h4 class="au"><?= $param ?></h4>
                    <ul class="list-unstyled">
                        <?php
                            foreach($values as $j => $value) {
                                $is_checked = FALSE;
                                if(!empty($settings[$param])) {
                                    $exploaded_values = explode('/', $settings[$param]);
                                    if(array_search($param, $exploaded_params)) {
                                        $is_checked = TRUE;
                                    }
                                }
                        ?>
                        <li data-param="<?= $param ?>" data-value="<?= $value ?>">
                            <span class="fa-stack">
                                <i class="fa fa-square-o fa-stack-2x"></i>
                                <i class="fa fa-check fa-stack-1x checked <?= $is_checked ? '' : 'hidden_css' ?>"></i>
                            </span>
                            <?= $value ?>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="cart_prod back ptb col-xs-12 col-sm-8 col-md-9 col-lg-9">
    <!-- <div style="height: 120px;"><h4 class="main_title mbt">Результаты поиска :</h4></div> -->
    <div id="prods_container" class=""></div>
</div>
<script src="js/class/searchClass.js"></script>
<script>

	$(document).ready(function() {

        Search.items_container = $('#prods_container');
        Search.drawFunc = prodBlock;
        Search.query = '<?= $query ?>';
        Search.page = <?= $page ?>;
        Search.category = '<?= $category ?>';
        Search.sort = '<?= $sort ?>';
        Search.direction = '<?= $direction ?>';
        Search.settings.val = <?= !empty($settings) ? json_encode($settings) : '{}' ?>;
        
        Search.draw(<?= json_encode($prods) ?>);

        $('.list ul li').click(function() {
            var checked = $(this).find('.checked');

            if(checked.hasClass('hidden_css')) {
                checked.removeClass('hidden_css');
                Search.settings.add($(this).attr('data-param'), $(this).attr('data-value'));
            } else {
                checked.addClass('hidden_css');
                Search.settings.delete($(this).attr('data-param'), $(this).attr('data-value'));
            }

            Search.update();
        });
    });
</script>
