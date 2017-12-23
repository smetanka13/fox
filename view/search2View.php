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

<link rel="stylesheet" type="text/css" href="css/cart.css">
<link rel="stylesheet" type="text/css" href="css/product_block.css">

<div class="reg_prod col-xs-12 col-sm-5 col-md-4 col-lg-4">
	<div class="reg_prod_cnt" id="menu_f">
		<?php
            $categories = Category::getCategories();
            foreach($categories as $index => $category) {
                $subcategories = Category::getValues($category, 'Подкатегория');
        ?>
        <!-- ЗДЕСЬ ВСТАВЛЯЕШЬ БЛОКИ ФИЛЬТРОВ -->
        <div class="catalog">
            <div class="cnt">
                <div class="name" data-id="<?= $index ?>">
                    <?= $category ?>
                    <button><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 533.333 533.333" style="enable-background:new 0 0 533.333 533.333;" xml:space="preserve"><g><path d="M516.667,200H333.333V16.667C333.333,7.462,325.871,0,316.667,0h-100C207.462,0,200,7.462,200,16.667V200H16.667   C7.462,200,0,207.462,0,216.667v100c0,9.204,7.462,16.666,16.667,16.666H200v183.334c0,9.204,7.462,16.666,16.667,16.666h100   c9.204,0,16.667-7.462,16.667-16.666V333.333h183.333c9.204,0,16.667-7.462,16.667-16.666v-100   C533.333,207.462,525.871,200,516.667,200z"/></g></svg></button>
                </div>
                <div class="list">
                    <?php
                        foreach($subcategories as $subcategory) {
                            $values = base64_encode(json_encode([
                                'Подкатегория' => $subcategory
                            ]));
                            echo '<a href="search?category='.$category.'&values='.$values.'"><div class="item">'.$subcategory.'</div></a>';
                        }
                    ?>
                    <div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 1 <small>(+ 50)</small></label>
    				</div>
    				<div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 2 <small>(+ 50)</small></label>
    				</div>
    				<div class="checkbox filt_cb">
    				  <label><input type="checkbox" value="">Option 3 <small>(+ 50)</small></label>
    				</div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="cart_prod col-xs-12 col-sm-7 col-md-8 col-lg-8">
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

<script type="text/javascript">
	$(document).ready(function() {
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
    });
</script>
