<link rel="stylesheet" type="text/css" href="css/header.css">

<nav class="navbar header " role="navigation">
    <div class="container-fluid">
    <!-- mobile version of header -->
        <div class="navbar-header color_head">
                <div id="mb_reg" data-toggle='modal' data-target='#signin_modal'></div>
                <a href="<?= URL ?>"><div id="logo_mob"></div></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header_menu">
                <span><image class="menu_icon" src="images/icons/menu.svg"></span>
            </button>
        </div>
        <!-- nav_menu -->
        <div class="collapse navbar-collapse color_head" id="header_menu">
            <a href="pick"><div class="oil_parce"><span>ПОДБОР МАСЛА</span><img src="images/icons/drop.svg"></div></a>

                <form class="visible-xs xs_form">
                  <div class="form-group">
                    <input type="search" value="<?= isset($_GET['srch']) ? $_GET['srch'] : '' ?>" class="form-control"  placeholder="Нажмите для поиска">
                    <img src="images/icons/search.svg">
                  </div>
                  <div id="callback_mob" class="form-group tel">
                    <input type="tel" class="form-control"  placeholder="Перезвонить">
                    <img src="images/icons/call_back.svg">
                  </div>
                </form>

                <ul class="nav navbar-nav nav_bar_pages">
                    <li><a href="<?= URL ?>">Главная</a></li>
                    <li><a href="blog">Статьи</a></li>
                    <li><a href="delivery">Доставка и оплата</a></li>
                    <li><a href="callback">Отзывы</a></li>
                    <li><a href="contacts">Контакты</a></li>
                </ul>

            <a href="cart"><div class="cart">
                <div><span class="badge">0</span></div>
                <ul class="list-unstyled">
                    <li>Корзина</li>
                    <li>&euro;: 0.00</li>
                </ul>
                <p class="visible-xs">&euro;: 0.00</p>
            </div></a>

        </div><!-- /.navbar-collapse -->
        <div class="sec_head hidden-xs">
            <div class="container-fluid white_part">
                <div class="white_part_back">
                    <div class="head_inf">
                        <table class="table">
                          <tbody>
                            <tr>
                              <td><img src="images/icons/phone.svg">+38 (067) 6973333</td>
                              <td><img src="images/icons/time_work.svg">с 9:00 до 17:00</td>
                            </tr>
                            <tr>
                              <td>+38 (067) 6973333</td>
                              <td><img src="images/icons/address.svg">г.Одесса</td>
                            </tr>
                            <tr>
                              <td><img id="call_back" src="images/icons/call_back.svg"><input type="tel" class="form-control" id="callback" placeholder="Перезвонить"></td>
                              <td><img src="images/icons/sms.svg">Авторынок Куяльник</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                    <div class="sec_inf_part">
                        <div class="reglog" >
                            <img src="images/icons/user.svg">
                           <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
                            <span data-toggle='modal' data-target='#signin_modal'>Вход/Регистрация</span>
                            <!-- после авторизации -->
                            <!-- <a href="personal_room"><span>Личный кабинет</span></a> -->
                        </div>
                    </div>
                </div>
                <a href="<?= URL ?>"><div class="logo"></div></a>
            </div>
            <ul class="nav navbar-nav nav_bar_pages2">
                <?php foreach ( Category::getCategories() as $val ) { ?>
                <li><a href="search?category=<?= $val ?>"><?= $val ?></a></li>
                <?php } ?>
            </ul>
            <?php if(URI != 'search') { ?>
            <form>
              <div class="form-group">
                <input type="search" class="form-control"  placeholder="Нажмите для поиска">
                <a href="search"><img id="sch_icn" src="images/icons/search.svg"></a>
              </div>
            </form>
            <?php } ?>
        </div>
    </div><!-- /.container-fluid -->
</nav>

<!-- FOR CALLBACK MODAL -->
<div id="callback_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">Мы Вам перезвоним !</h4>
      </div>
    </div>
  </div>
</div>

<!-- FOR EMBLEM ANIMATE -->
<script src="bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {

        Cart.updateVisual();

    	$('.table #callback').mask("+38 (099) 999-99-99", {autoclear: false});
      $('#callback_mob input').mask("+38 (099) 999-99-99", {autoclear: false});

        $(window).scroll(function(){

            if ($('body').width()>= 768) {
                if ($(this).scrollTop() > 250) {

                    $('.sec_head').hide(),
                    $('.header').addClass('navbar-fixed-top')

                } else {
                    $('.header').removeClass('navbar-fixed-top'),
                    $('.sec_head').slideDown(0)
                }
            }
            if ($('body').width() <= 767) {

                $('.header').addClass('navbar-fixed-top');
                if ($(this).scrollTop() > 66) {

                    $('#content').css('padding-top','66px');
                }
            }
        });

        // ДЛЯ ОПОВЕЩЕНИЯ ОБ ОБРАТНОЙ СВЯЗИ
      $('.table #call_back').click(function callback() {
        FW.ajax.send ({
    			model: 'callback',
    			method: 'add',
    			callback: function(data) {
    				if(data.status) {
              $('#callback_modal').modal('show');
              setTimeout(function(){
                  $('#callback_modal').modal('hide');
              }, 1000);
    				} else {
              FW.showMessage(data.error);
    				}
    			},
          data: {
            phone: $('.table #callback').val()
          }
    		});
      });
      $('#callback_mob img').click(function callback() {
        FW.ajax.send ({
    			model: 'callback',
    			method: 'add',
    			callback: function(data) {
    				if(data.status) {
              $('#callback_modal').modal('show');
              setTimeout(function(){
                  $('#callback_modal').modal('hide');
              }, 1000);
    				} else {
              FW.showMessage(data.error);
    				}
    			},
          data: {
            phone: $('#callback_mob input').val()
          }
    		});
      });
    });
</script>
