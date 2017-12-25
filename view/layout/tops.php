<?php
    # --- Если прошло больше 24 часов, то обновить ТОП --- #
    # --- (если это делать каждый раз когда заходит юзер, то это замедлит работу сайта) --- #

    require_once 'model/topsModel.php';

    Tops::updateTopProds();

?>

<link rel="stylesheet" type="text/css" href="css/product_block.css">

<!-- Топ самых часто продаваемых товаров -->
<div id="product_block">
    <div class="product_cnt" id="hot">
        <div class="prods_header">ТОП ПРОДАЖ</div>
        <button class="left_arrow" data-index="0"></button>
        <button class="right_arrow" data-index="0"></button>
        <div class="prod_viewport">
            <div class="prods_slider"></div>
        </div>
    </div>

    <!-- Топ самых новых товаров -->
    <div class="product_cnt" id="new">
        <div class="prods_header">НОВИНКИ</div>
        <button class="left_arrow" data-index="1"></button>
        <button class="right_arrow" data-index="1"></button>
        <div class="prod_viewport">
            <div class="prods_slider"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var offset = [0, 0];

        $('.right_arrow').click(function() {
            var index = $(this).attr('data-index');
            if(offset[index] <= -1500) return;
            offset[index] -= $('.prod_viewport:eq('+index+')').width();
            if(offset[index] <= -1500) offset[index] = -1500;
            $('.prods_slider:eq('+index+')').css('left', offset[index]+'px');
        });
        $('.left_arrow').click(function() {
            var index = $(this).attr('data-index');
            if(offset[index] >= 0) return;
            offset[index] += $('.prod_viewport:eq('+index+')').width();
            if(offset[index] >= 0) offset[index] = 0;
            $('.prods_slider:eq('+index+')').css('left', offset[index]+'px');
        });
        // $('.prods_slider').addClass("hidden").viewportChecker({
        //     classToAdd:'visible animated fadeIn',
        //     offset:100
        // });

        var newTopData = <?= json_encode(Tops::getNewProds(), JSON_UNESCAPED_UNICODE) ?>;
        var hotTopData = <?= json_encode(Tops::getHotProds(), JSON_UNESCAPED_UNICODE) ?>;

        for(i in newTopData) {
            $('.product_cnt#new .prods_slider').append(prodBlock(newTopData[i]));
        }
        for(i in hotTopData) {
            $('.product_cnt#hot .prods_slider').append(prodBlock(hotTopData[i]));
        }
    });
</script>

<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('.prods_header').addClass("hidden_css").viewportChecker({
            classToAdd:'visible_css animated fadeInUp'
        });
        $('.prod_viewport').addClass("hidden_css").viewportChecker({
            classToAdd:'visible_css animated fadeInUp'
        });
    });
</script> -->
