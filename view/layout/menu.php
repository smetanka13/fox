<link rel="stylesheet" type="text/css" href="css/menu.css">

<div id="menu">
    <div id="close_butt" onclick="trigMenu()"></div>
    <div class="heading mbt">Каталог</div>

    <?php
        $categories = Category::getCategories();
        foreach($categories as $index => $category) {
            $subcategories = Category::getValues($category, 'Подкатегория');
    ?>

    <div class="catalog">
        <div class="cnt">
            <div class="item">
                <div class="name"><?= $category ?></div>
                <div class="list">
                    <?php
                        foreach($subcategories as $subcategory) {
                            $values = base64_encode(json_encode([
                                'Подкатегория' => $subcategory
                            ]));
                            echo '<a href="search?category='.$category.'&settings='.$values.'"><div>'.$subcategory.'</div></a>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>
</div>

<script type="text/javascript">
    function trigMenu() {
        if($('#menu').hasClass('opened')) {
            $('#menu').removeClass('opened');
        } else {
            $('#menu').addClass('opened');
        }
    }
</script>
