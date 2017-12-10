<!-- <div class="dark_back" onclick="changeView('close')"></div>
<div class="float_block">
    <div class="float_view" id="enter">
        <div>
            <h3>E-mail</h3>
            <input type="text" placeholder="Введите ваш e-mail" id="email">
        </div>
        <div>
            <h3 id="ent_pass">Пароль</h3>
            <input type="password" placeholder="Введите ваш пароль" id="pass">
        </div>
        <button class="buttons enter_but ent_but_top" onclick="ajaxController({
            listener: 'login',
            callback: login,
            email: $('#enter_email').val(),
            pass: $('#enter_pass').val()
        })">ВХОД</button>
        <button onclick="changeView('reg')">РЕГИСТРАЦИЯ</button>
    </div>
    <div class="reg_view float_view">
        <div class="enter_inf">
            <img class="back_arr" onclick="floatBlock('enter')" src="images/icons/back_arrow.svg">
            <h3>Имя</h3>
            <input class="reg_info" id="public" type="text" placeholder="Введите ваше имя">
        </div>
        <div class="enter_inf">
            <h3>Телефон</h3>
            <input class="reg_info" id="phone" type="tel" placeholder="Введите ваш телефон">
        </div>
        <div class="enter_inf">
            <h3>E-mail *</h3>
            <input class="reg_info" id="email" type="text" placeholder="Введите ваш e-mail">
        </div>
        <div class="enter_inf">
            <h3>Пароль *</h3>
            <input class="reg_info" id="pass" type="password" placeholder="Введите ваш пароль">
        </div>
        <div class="enter_inf">
            <h3>Повторите пароль *</h3>
            <input class="reg_info" id="confirm" type="password" placeholder="Введите ваш пароль">
        </div>
        <button class="buttons" onclick="ajaxController({
            listener: 'reg',
            callback: reg,
            email: $('#reg_email').val(),
            pass: $('#reg_pass').val(),
            confirm: $('#reg_confirm').val(),
            public: $('#reg_public').val(),
            phone: $('#reg_phone').val()
        })">РЕГИСТРАЦИЯ</button>
    </div>
</div>

<script>
    $('#reg_phone').mask("+38 (099) 999-99-99", {autoclear: false});
    function changeView(view) {
        if($('.dark_back').css('display') == 'none') {
            $('.dark_back').fadeIn(300);
            $('.float_block').fadeIn(300);
        }
        if(view == 'close') {
            $('.dark_back').fadeOut(300);
            $('.float_block').fadeOut(300);
        }
        $('.float_view').hide();
        $('#'+view).show();
    }
    function loginCallback(json) {
        data = JSON.parse(json);
        if(data.status == false) {
            if(typeof(data.user) != 'undefined') {
                error('#enter_email', data.user);
                error('#enter_pass', data.user);
            }
        } else {
            $(document).attr('location', '<?= URL ?>/personal');
        }
    }
    function regCallback(json) {
        data = JSON.parse(json);
        if(data.status == false) {
            if(typeof(data.email) != 'undefined') {
                error('#reg_email', data.email);
            }
            if(typeof(data.pass) != 'undefined') {
                error('#reg_pass', data.pass);
            }
            if(typeof(data.confirm) != 'undefined') {
                error('#reg_confirm', data.confirm);
            }
            if(typeof(data.system) != 'undefined') {
                error('#reg_system', data.system);
            }
            if(typeof(data.phone) != 'undefined') {
                error('#reg_phone', data.phone);
            }
        } else {
            $(document).attr('location', URL);
        }
    }
    function error(id, msg) {
        $('.enter_inf input').removeAttr('title');
        $(id).attr('title', msg).animateCss('shake');
    }
</script> -->


<!-- FOR LOGIN -->
        <div id="signin_modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content siglog_window">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title main_title">Вход</h4>
                    </div>
                        <div class="modal-body">
                        <form class="enter_reg_place" action="../cgi/log_in.php" method="post">
                          <div class="form-group">
                            <label for="inputLogin_2" class="enter_reg_lable"><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> Login:</label>
                            <input type="text" class="form-control" id="inputLogin_2" name="login" placeholder="Введите login">
                          </div>
                          <!--  <div class="form-group">
                            <label for="inputEmail" class="enter_reg_lable">Email:</label>
                            <input type="email" class="form-control" id="inputEmail" placeholder="Введите email">
                          </div> -->
                          <div class="form-group">
                            <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-unlock-alt fa-lg fa-fw" aria-hidden="true"></i> Пароль:</label>
                            <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Введите пароль">
                          </div>
                          <div class="checkbox hidden-xs">
                            <label>
                              <input type="checkbox">Запомнить
                            </label>
                            <a id="fg_pass" data-toggle='modal' data-target='#pass_modal' class="pass_get">Забыли пароль?</a>
                          </div>

                          <div class="form-group visible-xs">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox">Запомнить
                                </label>
                                <div><a data-toggle='modal' data-target='#pass_modal' class="pass_get">Забыли пароль?</a></div>
                              </div>
                          </div>
                          <button type="submit" class="btn confirm_but">Войти</button>
                          <label class="rg_no">Еще не зарегистрированы?</label>
                          <div id="no_reg" type="submit" class="btn confirm_but " data-toggle='modal' data-target='#regist_modal'>Зарегистрироваться</div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- FOR REGISTRATION -->
    <div id="regist_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content siglog_window">
                <div class="modal-header">
                        <button class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title main_title">Регистрация</h4>
                </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="inputEmail" class="enter_reg_lable"><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i> Email:</label>
                        <input type="email" class="form-control" id="inputEmail" name="mail" placeholder="Введите email">
                      </div>
                      <div class="form-group">
                        <label for="inputText" class="enter_reg_lable"><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> Логин:</label>
                        <input type="text" class="form-control" id="inputLogin" name="login" placeholder="Введите логин">
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-unlock-alt fa-lg fa-fw" aria-hidden="true"></i> Пароль:</label>
                        <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Введите пароль">
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i> Подтвердите пароль:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="cpass" placeholder="Введите пароль">
                      </div>

                        <input type="text" style="display: none" name="role" value="14">

                      <button class="btn confirm_but" onclick="ajaxController({
                          model: 'user',
                          method: 'registrate',
                          callback: foo,
                          login: $('#inputLogin').val(),
                          email: $('#inputEmail').val(),
                          pass: $('#regist_modal #inputPassword').val(),
                          confirm: $('#confirmPassword').val()
                      })">Зарегистрироваться</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOR LOGOUT -->
    <div id="logout_bar" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content siglog_window">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title main_title"><i class="fa fa-sign-out fa-lg fa-fw" aria-hidden="true"></i> Выйти из аккаунта?</h4>
                </div>
                <div class="modal-body">
                    <form class="enter_reg_place">
                      <button type="submit" class="wth_boot_but confirm_but log_out_but"  onclick="exit();">Да</button>
                      <button type="submit" class="wth_boot_but confirm_but log_out_but">Нет</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FOR PASS FORGOT -->
    <div id="pass_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content siglog_window">
                <div class="modal-header">
                        <button class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title main_title">Забыли пароль</h4>
                </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="inputEmail" class="enter_reg_lable"><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i> Введите Email  и мы вышлем Вам подтверждение на почту:</label>
                        <input type="email" class="form-control" id="inputEmail" name="mail" placeholder="Введите email">
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-unlock-alt fa-lg fa-fw" aria-hidden="true"></i> Введите старый пароль:</label>
                        <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Введите пароль">
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-unlock-alt fa-lg fa-fw" aria-hidden="true"></i> Введите новый пароль:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="cpass" placeholder="Введите пароль">
                      </div>
                      <button class="btn confirm_but">Отправить</button>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    function foo() {
        
    }

    // for modals
    $('#no_reg').click(function(){
        $('#signin_modal').modal('hide');
    });
    $('.pass_get').click(function(){
        $('#signin_modal').modal('hide');
    });
</script>
