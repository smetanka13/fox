<?php
    # --- Если прошло больше 24 часов, то обновить ТОП --- #
    # --- (если это делать каждый раз когда зоходит юзер, то это замедлит работу сайта) --- #

    require_once 'model/productModel.php';
    require_once 'model/topsModel.php';

    Tops::updateTopProds();

?>

<link rel="stylesheet" type="text/css" href="css/product_block.css">
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
    });
</script>

<!-- Топ самых часто продаваемых товаров -->
<div id="product_block">
    <div class="product_cnt">
        <div class="prods_header">ТОП ПРОДАЖ</div>
        <button class="left_arrow" data-index="0"></button>
        <button class="right_arrow" data-index="0"></button>
        <div class="prod_viewport">
            <div class="prods_slider">
                <?php
                    # --- Вывод товаров --- #

                    $products = Tops::getTopProds();
                    foreach($products as $product) {
                        $params = Category::getParams($product['category']);
                ?>
                <div class="prods_cnt">
                    <div class="prods_wrapper">
                        <h3 class="title"><?= $product['title'] ?></h3>
                        <div class="prods_img_cnt"><img src="<?=
                            !empty($product['image']) ?
                            'catalog/'.$product['category'].'/'.$product['image'] :
                            'images/icons/no_photo.svg'
                        ?>"></div>
                        <p>
                            <?php
                                # --- Вывод параметров товара (Внутри ПАРАГРАФА) --- #
                                foreach($params as $param) {
                                    if(!empty($product[$param]))
                                        echo $param.': '.$product[$param].'</br>';
                                }
                            ?>
                        </p>
                        <div class="prods_bottom">
                            <a href="product?category=<?= $product['category'] ?>&id=<?= $product['id'] ?>"><button>КУПИТЬ</button></a>
                            <h4><?= $product['price']?> &euro;</h4>
                        </div>
                    </div>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Топ самых новых товаров -->
    <div class="product_cnt">
        <div class="prods_header">НОВИНКИ</div>
        <button class="left_arrow" data-index="1"></button>
        <button class="right_arrow" data-index="1"></button>
        <div class="prod_viewport">

            <div class="prods_slider">
                <?php
                    # --- Вывод товаров --- #

                    $products = Tops::getNewProds();
                    foreach($products as $product) {
                        $params = Category::getParams($product['category']);
                ?>
                <div class="prods_cnt">
                    <div class="prods_wrapper">
                        <h3 class="title"><?= $product['title'] ?></h3>
                        <div class="prods_img_cnt"><img src="<?=
                            !empty($product['image']) ?
                            'catalog/'.$product['category'].'/'.$product['image'] :
                            'images/icons/no_photo.svg'
                        ?>"></div>
                        <p>
                            <?php
                                # --- Вывод параметров товара (Внутри ПАРАГРАФА) --- #
                                foreach($params as $param) {
                                    if(!empty($product[$param]))
                                        echo $param.': '.$product[$param].'</br>';
                                }
                            ?>
                        </p>
                        <div class="prods_bottom">
                            <a href="product?category=<?= $product['category'] ?>&id=<?= $product['id'] ?>"><button>КУПИТЬ</button></a>
                            <h4><?= $product['price']?> &euro;</h4>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.prods_header').addClass("hidden_css").viewportChecker({
            classToAdd:'visible_css animated fadeInUp'
        });
        $('.prod_viewport').addClass("hidden_css").viewportChecker({
            classToAdd:'visible_css animated fadeInUp'
        });
    });
</script>
