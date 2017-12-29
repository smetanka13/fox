<link rel="stylesheet" type="text/css" href="css/menu.css">

<div id="menu" class="">
    <div id="close_butt" onclick="trigMenu()"></div>
    <div class="heading mbt">Каталог</div>

    <?php
        $categories = Category::getCategories();
        foreach($categories as $index => $category) {
            $subcategories = Category::getValues($category, 'Подкатегория');
    ?>

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
                        echo '<a href="search?category='.$category.'&settings='.$values.'"><div class="item"><i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true"></i> '.$subcategory.'</div></a>';
                    }
                ?>
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

    $(document).ready(function() {
        $('#menu .catalog .cnt .name').click(function() {
            var block = $('#menu .catalog .cnt:eq('+$(this).attr('data-id')+') .list');

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
