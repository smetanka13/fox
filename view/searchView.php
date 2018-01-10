<?php

    require 'model/searchModel.php';

    /* ---- Setup values ---- */

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'Масла';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'bought';
    $direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

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
	<div class="reg_prod_cnt sc_pr_cnt" id="menu_f">
    <div class="cat_cover">
      <ul class="list-unstyled">
        <h4 class="main_title tl_mg"><i class="fa fa-align-justify" aria-hidden="true"></i> Категории</h4>
        <li id="first">Масла</li>
        <li>Фильтры</li>
        <li>Ремни</li>
        <li>Присадки</li>
        <li>Тормозная система</li>
        <li>Стеклочистители</li>
        <li>Аккумуляторы</li>
      </ul>
    </div>
        <!-- ЗДЕСЬ ВСТАВЛЯЕШЬ БЛОКИ ФИЛЬТРОВ -->
        <div class="catalog">
            <div class="cnt">
                <div class="list pbm">
                  <p class="main_title"><i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i> вернуться к выбору</p>
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
                            <span class="fa-stack check_c">
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
<div class="search_pr_cnt cart_prod  ptb col-xs-12 col-sm-8 col-md-9 col-lg-9">
    <!-- <div style="height: 120px;"><h4 class="main_title mbt">Результаты поиска :</h4></div> -->
    <div class="tr_cnt">
      <button data-toggle="tooltip" data-placement="auto left" title="Нажмите, чтобы очистить поиск" id="trash_but" class="wth_boot_but confirm_but"><i class="fa fa-trash fa-2x " aria-hidden="true"></i></button>
    </div>
    <div id="prods_container" class="product_cnt"></div>
    <div class="pag_cnt">
      <ul class="pagination">
        <li class="disabled"><a href="#">«</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">»</a></li>
      </ul>
    </div>
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
            var checked_c = $(this).find('.check_c');

            if(checked.hasClass('hidden_css')) {
                checked.removeClass('hidden_css');
                checked_c.css('color','#F7931E');
                Search.settings.add($(this).attr('data-param'), $(this).attr('data-value'));
            } else {
                checked.addClass('hidden_css');
                Search.settings.delete($(this).attr('data-param'), $(this).attr('data-value'));
                checked_c.css('color','#333');
            }

            Search.update(updateSrchHistory);
        });
    });

    function updateSrchHistory() {
        history.pushState({
                query: Search.query,
                settings: Search.settings.val,
                sort: Search.sort,
                direction: Search.direction,
                page: Search.page
            },
            null,
            'search?query='+Search.query+'&page='+Search.page+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+FW.b64EncodeUnicode(JSON.stringify(Search.settings.val))
        );
    }

    window.onpopstate = function(event) {
        if(event && event.state) {
            Search.query = event.state.query;
            Search.page = event.state.page;
            Search.category = event.state.category;
            Search.sort = event.state.sort;
            Search.direction = event.state.direction;
            Search.settings.val = event.state.settings;
        }
        Search.update();
    }

    $('.cat_cover ul li').click(function(){
      $('.cat_cover').addClass('none_css');
    });
    $('.list p').click(function(){
      $('.cat_cover').removeClass('none_css');
    });
</script>
