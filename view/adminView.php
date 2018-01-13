<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" type="text/css" href="css/personal_room.css">


<div class="container- pr_main au btm">
    <h4 class="main_title tl_mg"></h4>
    <ul class="nav nav-tabs pr_tabs">
    <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> Поступающие заказы</a></li>
    <li><a data-toggle="tab" href="#menu1"><i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> Перезвонить</a></li>
    <li><a data-toggle="tab" href="#menu2"><i class="fa fa-download  fa-fw" aria-hidden="true"></i> Загрузка товаров</a></li>
    <li><a data-toggle="tab" href="#menu3"><i class="fa fa-download  fa-fw" aria-hidden="true"></i> Загрузка cтатей</a></li>
  </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <h4 class="pr_titles_cat">Поступающие заказы</h4>
            <p>Тут будут отображаться все заказы.</p>
            <p>Рядом в новыми заказами будет отображаться <i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i></p>
            <div class="container-fluid">
                <div class="row">
                    <div class="tb_cnt mbt">
                        <table id="order_table" class="table tb_buy table-bordered">
                            <thead>
                                <tr>
                                <th>№</th>
                                <th>ФИО клиента</th>
                                <th>Контактный телефон</th>
                                <th>Код товара</th>
                                <th>Количество</th>
                                <th>Сумма, грн</th>
                                <th>Способ оплаты</th>
                                <th>Способ доставки</th>
                                <th>Комментарий к заказу</th>
                                <th>Дата</th>
                                <th>Подтверждение заказа</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>3</td>
                                    <td>Фионов Юрий Сергеевич</td>
                                    <td>0939463704</td>
                                    <td><a href="#">67839992</td></a>
                                    <td>10 шт</td>
                                    <td>2000</td>
                                    <td>Оплата банковской картой</td>
                                    <td>Самовывоз</td>
                                    <td>Позвонить после принятия заказа</td>
                                    <td>20.10.2013</td>
                                    <td class="order">
                                        <div class="wth_boot_but ord_but">Подтвердить заказ</div>
                                        <div class="cf_ord">Заказ принят</div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu1" class="tab-pane fade">
          <h4 class="pr_titles_cat">Перезвоните по номеру</h4>
           <div class="container-fluid">
                <div class="row">
                    <div class="tb_cnt mbt">
                        <table class="table tb_buy table-bordered">
                            <thead>
                                <tr>
                                <th>Номер</th>
                                <th>Контактный телефон</th>
                                <th>Подтверждение</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>0939463704</td>
                                    <td class="order order_phone">
                                        <div class="wth_boot_but ord_but">Подтвердить</div>
                                        <div class="cf_ord">Принято</div>
                                    </td>
                                <tr>
                                    <td>2</td>
                                    <td>0939463704</td>
                                    <td class="order order_phone">
                                        <div class="wth_boot_but ord_but">Подтвердить</div>
                                        <div class="cf_ord">Принято</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>0939463704</td>
                                    <td class="order order_phone">
                                        <div class="wth_boot_but ord_but">Подтвердить</div>
                                        <div class="cf_ord">Принято</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@ MAIN @@@@@@@@@@@@@@@@@@@@@@@@@ -->

<div id="menu2" class="tab-pane fade">

<h4 class="pr_titles_cat">Загрузка товаров.</h4>
<p>Тут вы можете загрузить в базу данных новые товары.</p>

<div id="excel" class="col-md-12 cnt_all">
    <h1 class="main_title">Excel загрузка</h1>
    <input type="file" id="file" multiple="multiple">
    <button class="wth_boot_but confirm_but" onclick="FW.ajax.send({
        listener: 'excel',
        callback: callback
    }, {
        excel: $('#excel #file')
    })">Загрузить</button>
</div>
<div id="upload" class="col-md-12 cnt_all">
    <h1 class="main_title">Оформить товар</h1>
    <div class="alert alert-danger" role="alert" style="display: none">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        Товара с таким идентификатором не найдено.
    </div>
    <div class="alert alert-success" role="alert" style="display: none">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
        Найден товар: <a href=""></a>
    </div>
    <div class="container-fluid">
        <select id="category">
            <option>Не выбрано</option>
            <?php
                $categories = Category::getCategories();

                foreach($categories as $category) {
                    echo "<option>$category</option>";
                }
            ?>
        </select>
    </div>
    <input type="hidden" id="found_id" value="">
    <input type="text" id="id" placeholder="НАЗВАНИЕ / АРТИКУЛ">
    <input type="number" id="quantity" placeholder="Кол-во">
    <input type="text" id="price" placeholder="Цена">
    <textarea style="resize: both" id="text" placeholder="Описание"></textarea>
    Фото товара <br><input type="file" id="img" accept="image/jpeg,image/png">
    <div id="values"></div>
    <button class="wth_boot_but confirm_but" onclick="updateProd()">Оформить</button>
</div>
<div id="newcategory" class="col-md-12 cnt_all">
    <h1 class="main_title">Новая категория</h1>
    <input type="text" id="name" placeholder="Название категории">
    <div id="params_cnt">
        <input type="text" placeholder="Спецификация 1">
    </div>
    <div class="btn-group">
        <button class="btn btn-default" onclick="addInput('#newcategory #params_cnt', 'Спецификация')">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            Добавить спецификацию
        </button>
        <button class="btn btn-default" onclick="removeInput('#newcategory #params_cnt')">
            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
            Убрать спецификацию
        </button>
    </div>
    <button class="wth_boot_but confirm_but" onclick="addCategory()">Создать</button>
</div>
<div id="newparams" class="col-md-12 cnt_all">
    <h1 class="main_title">Добавить спецификацию категории</h1>
    <select id="category">
        <option>Не выбрано</option>
        <?php
            $categories = Category::getCategories();

            foreach($categories as $category) {
                echo "<option>$category</option>";
            }
        ?>
    </select>
    <input type="text" id="param" placeholder="Спецификация">
    <button class="wth_boot_but confirm_but" onclick="updateParams()">Создать</button>
</div>
<div id="newparamvalues" class="col-md-12 cnt_all">
    <h1 class="main_title">Добавить значения для спецификации</h1>
    <div class="container-fluid">
        <select id="category">
            <option>Не выбрано</option>
            <?php
                $categories = Category::getCategories();

                foreach($categories as $category) {
                    echo "<option>$category</option>";
                }
            ?>
        </select>
        <select id="param">
            <option>Не выбрано</option>
        </select>
    </div>
    <div class="btn-group">
        <button class="btn btn-default" onclick="addInput('#newparamvalues #values_cnt', 'Значение')">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            Добавить значение
        </button>
        <button class="btn btn-default" onclick="removeInput('#newparamvalues #values_cnt')">
            <span class="glyphicon glyphicon-minus inverse" aria-hidden="true"></span>
            Убрать значение
        </button>
    </div>
    <div id="values_cnt"></div>
    <button class="wth_boot_but confirm_but" onclick="updateValues()">Добавить</button>
</div>
</div>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@ MAIN @@@@@@@@@@@@@@@@@@@@@@@@@ -->

        <div id="menu3" class="tab-pane fade ns_cnt">
          <h4 class="pr_titles_cat">Загрузка статей.</h4>
          <p class="mbt">Тут вы можете загрузить новуя статью.</p>
              <div class="col-md-12 cnt_all">
                <div class="form-group">
                  <label>Загрузка заголовка</label>
                  <input id="title" type="text" class="form-control" placeholder="Введите заголовок">
                </div>
                <div class="form-group">
                  <label>Загрузка основного текста</label>
                  <textarea id="text" type="text" class="form-control" placeholder="Введите текст новости"></textarea>
                </div>
                <div class="form-group">
                  <label>Загрузка изображений (загрузите не более 4 изображений)</label>
                  <input id="images" type="file" class="form-control" multiple title="Загрузите фотографию">
                </div>
                <button onclick="FW.ajax.send({
                    model: 'article',
                    method: 'upload',
                    callback: function() {},
                    title: $('#menu3 #title').val(),
                    text: $('#menu3 #text').val()
                }, {
                    images: $('#menu3 #images')
                })" class="wth_boot_but confirm_but">Добавить</button>
            </div>
        </div>
    </div>
</div>

<script src="js/admin.main.js" charset="utf-8"></script>
<script>
    function callback(data) {
        if(data.status == true) {
            FW.showMessage('Успешная операция.');
        } else {
            FW.showMessage('Ошибка.');
        }
    }
</script>

<script type="text/javascript">
// function tableOrd(data){
//     var str = '';
//     for(var j in data.prods){
//         str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + data.prods[j].category + '&id=' + data.prods[j].id_prod + '"><li>' + data.prods[j].articule + '</li></a>';
//     }
//     return `
//         <tr id="`+data.id_order+`">
//             <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.id_order+`</td>
//             <td>${data.public}</td>
//             <td>`+data.phone+`</td>
//             <td><ul class="list-unstyled">`+str+`</ul></td>
//             <td>кол-во</td>
//             <td>`+data.prods[j].price+`</td>
//             <td>`+data.pay_way+`</td>
//             <td>`+data.delivery_way+`</td>
//             <td class="description">`+data.text+`</td>
//             <td>`+timeConverter(data.date)+`</td>
//             <td class="order order_ord form-group">
//                 <div id="`+data.id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
//                 <div id="`+data.id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
//                 <div id="`+data.id_order+`" class="cf_ord">Заказ принят</div>
//             </td>
//         </tr>
//     ` ;
// }
//
// $( document ).ready(function() {
//    setInterval(function(){
//         FW.ajax.send({
//             model: 'order',
//             method: 'getUnaccepted',
//             callback: function(data){
//                 $("#order_table tbody").empty();
//                 for( let index = data.output.length - 1; index >= 0 ; --index){
//                     var str = '';
//                     for(let j in data.output[index].prods){
//                         var id_prod = data.output[index].prods[j].id_prod;
//                         var articule = data.output[index].prods[j].articule;
//                         var price = data.output[index].prods[j].price;
//                         var category = data.output[index].prods[j].category;
//
//                         str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
//                     }
//                     $('#order_table ul').html(str);
//                     $('#order_table tbody').append(`
//                         <tr id="`+data.output[index].id_order+`">
//                             <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.output[index].id_order+`</td>
//                             <td>${data.output[index].public}</td>
//                             <td>`+data.output[index].phone+`</td>
//                             <td><ul class="list-unstyled"></ul></td>
//                             <td>кол-во</td>
//                             <td>`+price+`</td>
//                             <td>`+data.output[index].pay_way+`</td>
//                             <td>`+data.output[index].delivery_way+`</td>
//                             <td class="description">`+data.output[index].text+`</td>
//                             <td>`+timeConverter(data.output[index].date)+`</td>
//                             <td class="order order_ord form-group">
//                                 <div id="`+data.output[index].id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
//                                 <div id="`+data.output[index].id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
//                                 <div id="`+data.output[index].id_order+`" class="cf_ord">Заказ принят</div>
//                             </td>
//                         </tr>
//                     `);
//
//                     $('#order_table').mouseover(function(){
//                         if (data.output[index].checked == 0) {
//                             alert();
//                             FW.ajax.send({
//                                 model: 'order',
//                                 method: 'check',
//                                 callback:function(data){
//                                     $('#order_table i').addClass('hidden_css');
//                                 }
//                              });
//                         }
//                     });
//                 }
//             }
//         }
//     });
// }
// $( document ).ready(function() {
//     getUnaccepted();
//     setInterval(getUnaccepted,10000);
    // $(document).on('click','.order_ord', function(){
    //     var order_but = $(this).find('.'+data.output[index].id_order+'');
    //     var confirm_but = $(this).find('.'+data.output[index].id_order+'');

    //     if (order_but.css('display') == 'block') {
    //         order_but.fadeOut(0);
    //         confirm_but.addClass('text-success').fadeIn(0);
    //     }
    // });
    // $('.order_phone').click(
    //     function(){
    //         var order_but = $(this).find('.ord_but');
    //         var confirm_but = $(this).find('.cf_ord');

    //         if (order_but.css('display') == 'block') {
    //             order_but.fadeOut(0);
    //             confirm_but.addClass('text-success').fadeIn(0);
    //         }
    //     }
    // );
// });
</script>
