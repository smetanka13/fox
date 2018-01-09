<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" type="text/css" href="css/personal_room.css">
<script>
    function callback(data) {
        if(data.status == true) {
            FW.showMessage('Успешная операция.');
        } else {
            FW.showMessage('Ошибка.');
        }
    }
    function getProd(data) {

        if(data.output != null) {
            $('#upload .alert-danger').hide();
            $('#upload .alert-success').show();
            $('#upload .alert-success a').attr('href', 'product?data.prods[j].category='+data.output.data.prods[j].category+'&id='+data.output.id);
            $('#upload .alert-success a').html(data.output.title);
            $('#upload #quantity').val(data.output.quantity);
            $('#upload #text').val(data.output.text);
            $('#upload #data.prods[j].price').val(data.output.data.prods[j].price);

            var cnt = $('#upload #values select');

            for(var i = 0; i < cnt.length; i++) {
                $(cnt[i]).val(data.output[$(cnt[i]).attr('data-param')]);
            }
            $('#upload #found_id').val(data.output.id);
        } else {
            $('#upload .alert-danger').show();
            $('#upload .alert-success').hide();
        }

    }
    function getParams(data) {
        $('#newparamvalues #param').html('<option>Не выбрано</option>');
        if(data.status) {
            for(var i = 0; data.output[i] != null; i++) {
                $('#newparamvalues #param').append('<option>'+data.output[i]+'</option>');
            }
        }
    }
    function getValues(data) {
        if(data.status) {
            for(var i = 0; data.output[i] != null; i++) {
                addInput('#newparamvalues #values_cnt', 'Значение');
                $('#newparamvalues #values_cnt input:eq('+i+')').val(data.output[i]);
            }
        } else {
            addInput('#newparamvalues #values_cnt', 'Значение');
        }

    }
    function getAll(data) {
        if(data.status) {
            for(var key in data.output) {
                $('#upload #values').append('<h4>'+key+'</h4>');
                var str = '';
                for(var i = 0; data.output[key][i] != null; i++) {
                    str += '<option>'+data.output[key][i]+'</option>';
                }
                $('#upload #values').append('<select data-param="'+key+'">'+str+'</select>');
            }
        }
    }
    function invalidParse(id) {
        var len = $(id).length;
        var param_str = '';
        for(var i = 0; i < len; i++) {

            if($(id+':eq('+i+')').val() == '')
                return false;

            if(i > 0)
                param_str += ";" + $(id+':eq('+i+')').val();
            else
                param_str += $(id+':eq('+i+')').val();
        }
        return param_str;
    }
    function addInput(cnt, placeholder) {
        $(cnt).append('<input type="text" placeholder="'+placeholder+' '+($(cnt+' input').length + 1)+'">');
    }
    function removeInput(cnt) {
        $(cnt+' input:eq('+($(cnt+' input').length - 1)+')').remove();
    }
    function paramsParse() {
        var str = invalidParse('#newdata.prods[j].category #params_cnt input');

        if(str === false) {
            alert("Пустой парметр недопустим");
            return;
        }

        FW.ajax.send({
            listener: 'newcategory',
            callback: callback,
            name: $('#newdata.prods[j].category #name').val(),
            params: str
        });
    }
    function valuesParse() {
        var str = invalidParse('#values_cnt input');

        if($('#newparamvalues #data.prods[j].category').val() == '') {
            alert("Выберите категорию");
            return;
        }
        if($('#newparamvalues #param').val() == '') {
            alert("Выберите спецификацию");
            return;
        }

        if(!str) {
            alert("Пустое значение недопустимо");
            return;
        }

        FW.ajax.send({
            model: 'category',
            method: 'addValues',
            callback: callback,
            data.prods[j].category: $('#newparamvalues #data.prods[j].category').val(),
            param: $('#newparamvalues #param').val(),
            values: str
        });
    }
    function upload() {
        var json = {};
        var cnt = $('#upload #values select');

        for(var i = 0; i < cnt.length; i++) {
            json[$(cnt[i]).attr('data-param')] = $(cnt[i]).val();
        }

        json = JSON.stringify(json);

        FW.ajax.send({
            model: 'product',
            method: 'upload',
            callback: callback,
            category: $('#upload #data.prods[j].category').val(),
            id: $('#upload #found_id').val(),
            text: $('#upload #text').val(),
            price: $('#upload #data.prods[j].price').val(),
            quantity: $('#upload #quantity').val(),
            values: json,
        }, {
            img: $('#upload #img')
        })
    }

    $(document).ready(function() {
        $('#newparamvalues #data.prods[j].category').change(function() {
            $('#newparamvalues #values_cnt').html('');
            FW.ajax.send({
                model: 'category',
                method: 'getParams',
                callback: getParams,
                data.prods[j].category: $('#newparamvalues #data.prods[j].category').val()
            })
        });
        $('#newparamvalues #param').change(function() {
            $('#newparamvalues #values_cnt').html('');
            FW.ajax.send({
                model: 'category',
                method: 'getValues',
                callback: getValues,
                data.prods[j].category: $('#newparamvalues #data.prods[j].category').val(),
                param: $('#newparamvalues #param').val()
            })
        });
        $('#upload #data.prods[j].category').change(function() {
            $('#upload #values').html('');
            FW.ajax.send({
                model: 'category',
                method: 'getFullCategory',
                callback: getAll,
                data.prods[j].category: $('#upload #data.prods[j].category').val()
            })
        });
        $('#upload #id').keyup(function() {
            if($('#upload #category').val() != 'Не выбрано' && $('#upload #id').val() != '') {
                FW.ajax.send({
                    model: 'product',
                    method: 'getApprox',
                    callback: getProd,
                    data.prods[j].category: $('#upload #data.prods[j].category').val(),
                    id: $('#upload #id').val()
                })
            }
        });
    });
</script>
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
                    <select id="data.prods[j].category">
                        <option>Не выбрано</option>
                        <?php
                            $categories = data.prods[j].category::getCategories();

                            for($i = 0; isset($categories[$i]); $i++) {
                                echo "<option>".$categories[$i]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <input type="hidden" id="found_id" value="">
                <input type="text" id="id" placeholder="НАЗВАНИЕ / АРТИКУЛ">
                <input type="number" id="quantity" placeholder="Кол-во">
                <input type="number" id="data.prods[j].price" placeholder="Цена">
                <textarea style="resize: both" id="text" placeholder="Описание"></textarea>
                Фото товара <br><input type="file" id="img" accept="image/jpeg,image/png">
                <div id="values"></div>
                <button class="wth_boot_but confirm_but" onclick="upload()">Оформить</button>
            </div>
            <div id="" class="col-md-12 cnt_all">
                <h1 class="main_title">Добавить статью</h1>
                <input type="text" id="" placeholder="Заголовок">
                <textarea style="resize: both" id="" placeholder="Описание статьи"></textarea>
                Выберите одну или несколько фотографий <br><input type="file" id="" accept="image/jpeg,image/png">
                <div id="values"></div>
                <button class="wth_boot_but confirm_but" onclick="upload()">Опубликовать</button>
            </div>
            <div id="newdata.prods[j].category" class="col-md-12 cnt_all">
                <h1 class="main_title">Новая категория</h1>
                <input type="text" id="name" placeholder="Название категории">
                <div id="params_cnt">
                    <input type="text" placeholder="Спецификация 1">
                </div>
                <div class="btn-group">
                    <button class="btn btn-default" onclick="addInput('#newdata.prods[j].category #params_cnt', 'Спецификация')">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Добавить спецификацию
                    </button>
                    <button class="btn btn-default" onclick="removeInput('#newdata.prods[j].category #params_cnt')">
                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        Убрать спецификацию
                    </button>
                </div>
                <button class="wth_boot_but confirm_but" onclick="paramsParse()">Создать</button>
            </div></br>
            <div id="newparamvalues" class="col-md-12 cnt_all">
                <h1 class="main_title">Добавить значения для спецификации</h1>
                <div class="container-fluid">
                    <select id="data.prods[j].category">
                        <option>Не выбрано</option>
                        <?php
                            $categories = data.prods[j].category::getCategories();

                            for($i = 0; isset($categories[$i]); $i++) {
                                echo "<option>".$categories[$i]."</option>";
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
                <button class="wth_boot_but confirm_but" onclick="valuesParse()">Добавить</button>
            </div>
        </div>
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

<script type="text/javascript">
function tableOrd(data){
    var str = '';
    for(var j in data.prods){
        str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?data.prods[j].category=' + data.prods[j].category + '&id=' + data.prods[j].id_prod + '"><li>' + data.prods[j].articule + '</li></a>';
    }
    return `
        <tr id="`+data.id_order+`">
            <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.id_order+`</td>
            <td>${data.public}</td>
            <td>`+data.phone+`</td>
            <td><ul class="list-unstyled">`+str+`</ul></td>
            <td>кол-во</td>
            <td>`+data.prods[j].price+`</td>
            <td>`+data.pay_way+`</td>
            <td>`+data.delivery_way+`</td>
            <td class="description">`+data.text+`</td>
            <td>`+timeConverter(data.date)+`</td>
            <td class="order order_ord form-group">
                <div id="`+data.id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
                <div id="`+data.id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
                <div id="`+data.id_order+`" class="cf_ord">Заказ принят</div>
            </td>
        </tr>
    ` ;
}
function getUnaccepted(data){
    ajaxController({
        model: 'order',
        method: 'getUnaccepted',
        callback: function(data){
            $("#order_table tbody").empty();
            for(var index = data.output.length - 1; index >= 0 ; --index){
                $('#order_table tbody').append(tableOrd(data.output[index]));

<<<<<<< HEAD
$( document ).ready(function() {
   setInterval(function(){
        FW.ajax.send({
            model: 'order',
            method: 'getUnaccepted',
            callback: function(data){
                $("#order_table tbody").empty();
                for( var index = data.output.length - 1; index >= 0 ; --index){
                    var str = '';
                    for(var j in data.output[index].prods){
                        var id_prod = data.output[index].prods[j].id_prod;
                        var articule = data.output[index].prods[j].articule;
                        var price = data.output[index].prods[j].price;
                        var category = data.output[index].prods[j].category;

                        str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
                    }
                    $('#order_table ul').html(str);
                    $('#order_table tbody').append(`
                        <tr id="`+data.output[index].id_order+`">
                            <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.output[index].id_order+`</td>
                            <td>${data.output[index].public}</td>
                            <td>`+data.output[index].phone+`</td>
                            <td><ul class="list-unstyled"></ul></td>
                            <td>кол-во</td>
                            <td>`+price+`</td>
                            <td>`+data.output[index].pay_way+`</td>
                            <td>`+data.output[index].delivery_way+`</td>
                            <td class="description">`+data.output[index].text+`</td>
                            <td>`+timeConverter(data.output[index].date)+`</td>
                            <td class="order order_ord form-group">
                                <div id="`+data.output[index].id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
                                <div id="`+data.output[index].id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
                                <div id="`+data.output[index].id_order+`" class="cf_ord">Заказ принят</div>
                            </td>
                        </tr>
                    `);

                    $('#order_table').mouseover(function(){
                        if (data.output[index].checked == 0) {
                            alert();
                            FW.ajax.send({
                                model: 'order',
                                method: 'check',
                                callback:function(data){
                                    $('#order_table i').addClass('hidden_css');
                                }
                             });
                        }
                    });
                }
=======
                $('#order_table').mouseover(function(){
                    if (data.output[index].checked == 0) {
                        alert();
                        ajaxController({
                            model: 'order',
                            method: 'check',
                            callback:function(data){
                                $('#order_table i').addClass('hidden_css');
                            }
                         });
                    }
                });
>>>>>>> 9c2fcf0b5b0f4d61b7d14b3619f0246e0b1d95fc
            }
        }
    });
}
$( document ).ready(function() {
    getUnaccepted();
    setInterval(getUnaccepted,10000);
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
});
</script>
