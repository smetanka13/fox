<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/search.css">

<div class="sider">

    <input type="text" placeholder="Поиск по словам" value="<?= $_DATA['query'] ?>">
    <button class="srch-btn">
        <i class="fa fa-search" aria-hidden="true"></i>
    </button>

    <?php
        $params = Category::getParams($_DATA['category']);
        foreach($params as $i => $param) {
            $values = Category::getValues($_DATA['category'], $param);
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
    <select class="sort-select">
        <option <?php if($_DATA['sort'] == 'bought' && $_DATA['direction'] == 'ASC') echo 'selected' ?> value="[&quot;bought&quot;, &quot;ASC&quot;]">популярные</option>
        <option <?php if($_DATA['sort'] == 'price' && $_DATA['direction'] == 'ASC') echo 'selected' ?> value="[&quot;price&quot;, &quot;ASC&quot;]">от дешевых к дорогим</option>
        <option <?php if($_DATA['sort'] == 'price' && $_DATA['direction'] == 'DESC') echo 'selected' ?> value="[&quot;price&quot;, &quot;DESC&quot;]">от дорогих к дешевым</option>
        <option <?php if($_DATA['sort'] == 'id_prod' && $_DATA['direction'] == 'ASC') echo 'selected' ?> value="[&quot;id_prod&quot;, &quot;ASC&quot;]">новинки</option>
    </select>
    <div id="prods_container" class="product-cnt"></div>
    <button class="more-btn fw-theme-metal-button">
        <i class="fa fa-angle-down" aria-hidden="true"></i>
    </button>
</div>
<script src="js/class/searchClass.js"></script>
<script>
	$(document).ready(function() {

        Search.items_container = $('#prods_container');
        Search.drawFunc = prodBlock;
        Search.query = '<?= $_DATA['query'] ?>';
        Search.page = 0;
        Search.category = '<?= $_DATA['category'] ?>';
        Search.sort = '<?= $_DATA['sort'] ?>';
        Search.direction = '<?= $_DATA['direction'] ?>';

        <?php if($_DATA['pages_left'] <= 0) { ?>
        $('.more-btn').hide();
        <?php } ?>

        Search.settings.val = <?= !empty($_DATA['settings']) ? json_encode($_DATA['settings'], JSON_UNESCAPED_UNICODE) : '{}' ?>;

        Search.draw(<?= json_encode($_DATA['prods'], JSON_UNESCAPED_UNICODE) ?>);

        $('.sort-select').selectric().on('change', function() {
            let data = JSON.parse($(this).val());
            Search.sort = data[0];
            Search.direction = data[1];
            Search.update(true, updateSrchCallback);
        });

        $('.more-btn').click(function() {
            Search.page += 1;
            Search.update(false, function(data) {
                if(data.pages_left <= 0) $('.more-btn').hide();
            });
        });

        $('.sider input').keypress(function(e) {
            if(e.which == 13) {
                Search.query = $(this).val();
                Search.update(true, updateSrchCallback);
            }
        });
        $('.sider .srch-btn').click(function() {
            Search.query = $('.sider input').val();
            Search.update(true, updateSrchCallback);
        });

        $('.sider ul li button').click(function() {
            var check = $(this).find('.check');

            if(check.hasClass('selected')) {
                Search.settings.delete($(this).attr('data-param'), $(this).attr('data-value'));
            } else {
                Search.settings.add($(this).attr('data-param'), $(this).attr('data-value'));
            }

            updateParamSelections();

            Search.update(true, updateSrchCallback);
        });

        history.replaceState(
            {
                settings: Search.settings.val,
                sort: Search.sort,
                direction: Search.direction,
                page: Search.page,
            },
            null,
            'search?query='+Search.query+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+FW.b64EncodeUnicode(JSON.stringify(Search.settings.val))
        );
        updateParamSelections();
    });

    function updateParamSelections() {
        $('.sider ul li button .check').removeClass('selected');

        for(let i in Search.settings.val) {
            for(let j in Search.settings.val[i]) {
                let param = $('.sider ul li button[data-param="'+i+'"][data-value="'+Search.settings.val[i][j]+'"]');
                if(param != null) {
                    param.find('.check').addClass('selected');
                }
            }
        }

    }

    function updateSrchCallback(data) {

        if(data.pages_left > 0) $('.more-btn').show();
        else $('.more-btn').hide();

        Search.page = 0;

        history.pushState(
            {
                settings: Search.settings.val,
                sort: Search.sort,
                direction: Search.direction,
                page: Search.page,
            },
            null,
            'search?query='+Search.query+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+encodeURI(FW.b64EncodeUnicode(JSON.stringify(Search.settings.val)))
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
</script>
