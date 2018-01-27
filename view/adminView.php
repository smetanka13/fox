<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" type="text/css" href="css/personal_room.css">


<div class="container- pr_main au btm">
    <h4 class="main_title tl_mg"></h4>
    <ul class="nav nav-tabs pr_tabs">
    <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> Поступающие заказы</a></li>
    <li><a data-toggle="tab" href="#menu4"><i class="fa fa-check-square  fa-fw" aria-hidden="true"></i> Принятые заказы</a></li>
    <li><a data-toggle="tab" href="#menu1"><i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> Перезвонить</a></li>
    <li><a data-toggle="tab" href="#menu2"><i class="fa fa-download  fa-fw" aria-hidden="true"></i> Загрузка товаров</a></li>
    <li><a data-toggle="tab" href="#menu3"><i class="fa fa-download  fa-fw" aria-hidden="true"></i> Загрузка cтатей</a></li>
  </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <h4 class="pr_titles_cat">Поступающие заказы</h4>
            <p>Тут будут отображаться все заказы.</p>
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
                                <th>Количество, шт</th>
                                <th>Сумма, грн</th>
                                <th>Способ оплаты</th>
                                <th>Способ доставки</th>
                                <th>Комментарий к заказу</th>
                                <th>Дата</th>
                                <th>Подтверждение заказа</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu4" class="tab-pane fade">
          <h4 class="pr_titles_cat">Принятые заказы</h4>
          <p>Тут будут отображаться все принятые заказы.</p>
          <div class="container-fluid">
              <div class="row">
                  <div class="tb_cnt mbt">
                      <table id="accept_order_table" class="table tb_buy table-bordered">
                          <thead>
                              <tr>
                              <th>№</th>
                              <th>ФИО клиента</th>
                              <th>Контактный телефон</th>
                              <th>Код товара</th>
                              <th>Количество, шт</th>
                              <th>Сумма, грн</th>
                              <th>Способ оплаты</th>
                              <th>Способ доставки</th>
                              <th>Комментарий к заказу</th>
                              <th>Дата</th>
                              <!-- <th>Статус заказа</th> -->
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                  </div>
              </div>
          </div>
        </div>
        <div id="menu1" class="tab-pane fade">
          <h4 class="pr_titles_cat">Перезвоните по номеру</h4>
          <p>После нажатия на кнопку подтвердить , номер удалится из таблицы.</p>
           <div class="container-fluid">
                <div class="row">
                    <div class="tb_cnt mbt">
                        <table id="accept_phone" class="table tb_buy table-bordered">
                            <thead>
                                <tr>
                                  <th>Контактный телефон</th>
                                  <th>Подтверждение</th>
                                  <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
<div id="exchange" class="col-md-12 cnt_all">
    <h1 class="main_title">Изменить курс на сайте (по отношению к евро)</h1>
    <div class="currencies">
        <?php
            require_once 'model/exchangeModel.php';
            $course = Exchange::get();
        ?>
        <input value="<?= $course['UAH'] ?>" type="text" placeholder="Гривна" min="0" step="any">
        <input value="<?= $course['USD'] ?>" type="text" placeholder="Доллар" min="0" step="any">
        <input value="<?= $course['RUB'] ?>" type="text" placeholder="Рубль" min="0" step="any">
    </div>

    <button class="wth_boot_but confirm_but" onclick="updateExchange()">Изменить</button>
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
    <!-- <div id="adm_dsc" class="form-group">
      <h1 class="main_title">Добавить скидку</h1>
      <input type="number" placeholder=""><span>%</span>
    </div> -->
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

    <!-- <h1 class="main_title">Добавить скидку к существующему товару</h1>
    <div class="alert alert-danger" role="alert" style="display: none">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        Товара с таким идентификатором не найдено.
    </div>
    <input type="text" id="id" placeholder="НАЗВАНИЕ / АРТИКУЛ">
    <div id="adm_dsc" class="form-group">
      <h1 class="main_title">Добавить процент скидки</h1>
      <input type="number" placeholder=""><span>%</span>
    </div>
    <button class="wth_boot_but confirm_but" onclick="updateValues()">Добавить</button> -->
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
                    data: {
                        title: $('#menu3 #title').val(),
                        text: $('#menu3 #text').val()
                    }
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
<script src="js/admin.sec.js" charset="utf-8"></script>
