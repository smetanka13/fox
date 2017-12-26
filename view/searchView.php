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


    $result = Search::find($page, $srch, $category, $values, $sort, $from);
    $prods = $result['search_result'];
?>

<link rel="stylesheet" type="text/css" href="css/cart.css">
<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/search2.css">

<div class="reg_prod col-xs-12 col-sm-4 col-md-3 col-lg-3">
	<div class="reg_prod_cnt" id="menu_f">
		<?php
            $categories = Category::getCategories();
            foreach($categories as $index => $category) {
                $subcategories = Category::getValues($category, 'Подкатегория');
        ?>
        <!-- ЗДЕСЬ ВСТАВЛЯЕШЬ БЛОКИ ФИЛЬТРОВ -->
        <div class="catalog">
            <div class="cnt">
                <div class="list pbm">
                    <h4 class="au">По такому-то критерию: </h4>
                    <!-- <div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 1 <small>(+ 50)</small></label>
    				</div>
    				<div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 2 <small>(+ 50)</small></label>
    				</div>
    				<div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 3 <small>(+ 50)</small></label>
    				</div> -->
                    <ul class="list-unstyled">
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                    </ul>
                    <h4 class="au">По такому-то критерию: </h4>
                    <ul class="list-unstyled">
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                        <li>
                            <span class="fa-stack">
                              <i class="fa fa-square-o fa-stack-2x"></i>
                              <i class="fa fa-check fa-stack-1x checked hidden_css"></i>
                            </span>
                            Option1<small>( + 50 )</small>
                        </li>
                    </ul>
                    <button class="wth_boot_but confirm_but au">готово</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="cart_prod back ptb col-xs-12 col-sm-8 col-md-9 col-lg-9">
    <!-- <div style="height: 120px;"><h4 class="main_title mbt">Результаты поиска :</h4></div> -->

</div>

<script type="text/javascript">

    var query = <?= $srch ?>;
    var page = <?= $page ?>;
    var category = <?= $category ?>;
    var sort = <?= $sort ?>;
    var from = <?= $from ?>;
    var params = <?= json_encode($values) ?>;

    function addParam() {

    }
    function updateSrch(srch, category, values, sort, from) {
        ajaxController({
            model: 'search',
            method: 'find',
            callback: function qwe() {

            },
            query: ,
            page: ,
            category: ,
            sort: ,
            from: ,
            values: ,
        });
    }
    function drawProds(data) {
        for(i in data) {
            $('.cart_prod').append(prodBlock(data[i]));
        }
    }

	$(document).ready(function() {

        updateSrch(<?= json_encode($prods) ?>);

        $('#menu_f .catalog .cnt .name').click(function() {
            var block = $('#menu_f .catalog .cnt:eq('+$(this).attr('data-id')+') .list');

            if(block.css('display') == 'none') {
                block.slideDown(100);
                $(this).addClass('opened');
            } else {
                block.slideUp(100);
                $(this).removeClass('opened');
            }
        });

        $('.list ul li').click(
            function(){
                var checked = $(this).find('.checked');

                if (checked.hasClass('hidden_css')) {
                    checked.removeClass('hidden_css');
                }else{
                    checked.addClass('hidden_css');
                }
            }
        );
    });
</script>
