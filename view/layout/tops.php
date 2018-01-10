<?php
    # --- Если прошло больше 24 часов, то обновить ТОП --- #
    # --- (если это делать каждый раз когда заходит юзер, то это замедлит работу сайта) --- #

    require_once 'model/topsModel.php';

    Tops::updateHotProds();

?>

<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/tops.css">

<!-- Топ самых часто продаваемых товаров -->
<div class="top_wrapper">
    <div class="top_cnt" id="hot">
        <div class="top_header">ТОП ПРОДАЖ</div>
        <div class="top_viewport">
            <div class="top_slider"></div>
        </div>
    </div>

    <!-- Топ самых новых товаров -->
    <div class="top_cnt" id="new">
        <div class="top_header">НОВИНКИ</div>
        <div class="top_viewport">
            <div class="top_slider"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        var new_top_data = <?= json_encode(Tops::getNewProds(), JSON_UNESCAPED_UNICODE) ?>;
        var hot_top_data = <?= json_encode(Tops::getHotProds(), JSON_UNESCAPED_UNICODE) ?>;

        for(let i in new_top_data) {
            $('.top_cnt#new .top_slider').append(prodBlock(new_top_data[i]));
        }
        for(let i in hot_top_data) {
            $('.top_cnt#hot .top_slider').append(prodBlock(hot_top_data[i]));
        }

    });
</script>
<script src="js/tops.js"></script>
