<?php

    require 'model/searchModel.php';

    /* ---- Setup values ---- */

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'Масла';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'bought';
    $direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

    if(isset($_GET['settings'])) {
        $settings = json_decode(
            utf8_encode(
                urlencode(
                    base64_decode(
                        $_GET['settings']
                    )
                )
            ),
            TRUE
        );
    } else {
        $settings = [];
    }

    $result = Search::find($page, $query, $category, $settings, $sort, $direction);
    $prods = $result['search_result'];
?>

<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/search.css">

<div class="sider">

    <input type="text" placeholder="Поиск по словам" value="<?= $query ?>">
    <button class="srch-btn">
        <i class="fa fa-search" aria-hidden="true"></i>
    </button>

    <?php
        $params = Category::getParams($category);
        foreach($params as $i => $param) {
            $values = Category::getValues($category, $param);
    ?>
    <h4 class="au"><?= $param ?></h4>
    <ul class="list-unstyled">
        <?php foreach($values as $j => $value) { ?>
        <li>
            <button data-param="<?= $param ?>" data-value="<?= $value ?>">
                <div class="check"></div>
                <?= $value ?>
            </button>
        </li>
        <?php } ?>
    </ul>
    <?php } ?>

</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="left-container">
    <?php print_r($settings); echo "<br>"; ?>
    <!-- <div class="tr_cnt">
        <div class="form-inline">
            <div class="form-group">
                <label for="sort"><i class="fa fa-sort fa-lg fa-fw" aria-hidden="true"></i></label>
                <select class="" name="sort">
                <option value="">популярные</option>
                <option value="">от дешевых к дорогим</option>
                <option value="">от дорогих к дешевым</option>
                <option value="">новинки</option>
                </select>
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" name="">АКЦИЯ</label>
            </div>
        </div>
        <button data-toggle="tooltip" data-placement="auto left" title="Нажмите, чтобы очистить поиск" id="trash_but" class="wth_boot_but confirm_but"><i class="fa fa-trash fa-2x " aria-hidden="true"></i></button>
    </div> -->
    <div id="prods_container" class="product-cnt"></div>
    <!-- <div class="pag_cnt">
        <ul class="pagination">
            <li class="disabled"><a href="#">«</a></li>
            <li class="active"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">»</a></li>
        </ul>
    </div> -->
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



        Search.settings.val = <?= !empty($settings) ? json_encode($settings, JSON_UNESCAPED_UNICODE) : '{}' ?>;



        Search.draw(<?= json_encode($prods, JSON_UNESCAPED_UNICODE) ?>);

        $('.sider input').keypress(function(e) {
            if(e.which == 13) {
                Search.query = $(this).val();
                Search.update(updateSrchHistory);
            }
        });
        $('.sider .srch-btn').click(function() {
            Search.query = $('.sider input').val();
            Search.update(updateSrchHistory);
        });

        $('.sider ul li button').click(function() {
            var check = $(this).find('.check');

            if(check.hasClass('selected')) {
                Search.settings.delete($(this).attr('data-param'), $(this).attr('data-value'));
            } else {
                Search.settings.add($(this).attr('data-param'), $(this).attr('data-value'));
            }

            updateParamSelections();

            Search.update(updateSrchHistory);
        });

        history.replaceState(
            {
                settings: Search.settings.val,
                sort: Search.sort,
                direction: Search.direction,
                page: Search.page,
            },
            null,
            'search?query='+Search.query+'&page='+Search.page+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+FW.b64EncodeUnicode(JSON.stringify(Search.settings.val))
        );
        updateParamSelections();
    });

    function updateParamSelections() {
        $('.sider ul li button .check').removeClass('selected');

        for(let i in Search.settings.val) {
            let exploaded_params = Search.settings.val[i].split('/');

            for(let j in exploaded_params) {
                let param = $('.sider ul li button[data-param="'+i+'"][data-value="'+exploaded_params[j]+'"]');
                if(param != null) {
                    param.find('.check').addClass('selected');
                }
            }
        }

    }

    function updateSrchHistory() {
        history.pushState(
            {
                settings: Search.settings.val,
                sort: Search.sort,
                direction: Search.direction,
                page: Search.page,
            },
            null,
            'search?query='+Search.query+'&page='+Search.page+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+FW.b64EncodeUnicode(JSON.stringify(Search.settings.val))
        );

    }

    window.onpopstate = function(event) {
        console.log(event.state);
        if(event && event.state) {
            Search.page = event.state.page;
            Search.sort = event.state.sort;
            Search.direction = event.state.direction;
            Search.settings.val = event.state.settings;
        }
        Search.update(updateParamSelections);
    }

    $('.cat_cover ul li').click(function(){
      $('.cat_cover').addClass('none_css');
    });
    $('.list p').click(function(){
      $('.cat_cover').removeClass('none_css');
    });
</script>
