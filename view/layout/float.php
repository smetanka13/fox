<!-- FOR LOGIN -->
        <div id="signin_modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content siglog_window">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title main_title">Вход</h4>
                    </div>
                        <div class="modal-body">
                        <form class="enter_reg_place">
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
                            <a id="fg_pass" data-toggle='modal' data-target='#pass_modal' class="pass_get">Забыли пароль?</a>
                          </div>

                          <div class="form-group visible-xs">
                              <div class="checkbox">
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
                        <h4 class="none_css empty_err">Заполните все поля !</h4>
                      <div class="form-group">
                        <label for="inputEmail" class="enter_reg_lable"><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i> Email:</label>
                        <input type="email" class="form-control" id="inputEmail" name="mail" placeholder="Введите email">
                      </div>
                      <div class="form-group">
                        <label for="inputText" class="enter_reg_lable"><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> Логин:</label>
                        <i id="lg_lh" class="none_css fa fa-question-circle fa-lg inf_inp" aria-hidden="true" data-toggle="tooltip" data-placement="auto left" title="Длина логина должна быть от 6 до 32 символов !"></i>
                        <input type="text" class="form-control" id="inputLogin" name="login" placeholder="Введите логин">
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="enter_reg_lable"><i class="fa fa-unlock-alt fa-lg fa-fw" aria-hidden="true"></i> Пароль:</label>
                        <i id="ps_lh" class="none_css fa fa-question-circle fa-lg inf_inp" aria-hidden="true" data-toggle="tooltip" data-placement="auto left" title="Длина пароля должна быть от 4 до 32 символов !"></i>
                        <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Введите пароль">
                      </div>
                      <div class="form-group">
                        <label for="confirmPassword" class="enter_reg_lable"><i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i> Подтвердите пароль:</label>
                        <!-- <i id="ps_rp" class="none_css fa fa-question-circle fa-lg inf_inp" aria-hidden="true" data-toggle="tooltip" data-placement="auto left" title="Повторите пароль !"></i> -->
                        <i id="ps_rptr" class="none_css fa fa-question-circle fa-lg inf_inp" aria-hidden="true" data-toggle="tooltip" data-placement="auto left" title="Повторите пароль верно !"></i>
                        <input type="password" class="form-control" id="confirmPassword" name="cpass" placeholder="Введите пароль">
                      </div>

                        <input type="text" style="display: none" name="role" value="14">

                      <button id="bt_reg" class="btn confirm_but">Зарегистрироваться</button>
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

    $('#bt_reg').click(function(){

        var empty_val = false;

        // проверяем поля на пустоту
        if ($('#regist_modal #inputEmail').val() == '') {
            $('#regist_modal #inputEmail').addClass('alert-danger');
            empty_val = true;
        }else{
            $('#regist_modal #inputEmail').removeClass('alert-danger');
            empty_val = false;
        }
        if ($('#regist_modal #inputLogin').val() == '') {
            $('#regist_modal #inputLogin').addClass('alert-danger');
            empty_val = true;
        }else{
            $('#regist_modal #inputLogin').removeClass('alert-danger');
            empty_val = false;
        }
        if ($('#regist_modal #inputPassword').val() == '') {
            $('#regist_modal #inputPassword').addClass('alert-danger');
            empty_val = true;
        }else{
            $('#regist_modal #inputPassword').removeClass('alert-danger');
            empty_val = false;
        }
        if ($('#regist_modal #confirmPassword').val() == '') {
            $('#regist_modal #confirmPassword').addClass('alert-danger');
            empty_val = true;
        }else{
            $('#regist_modal #confirmPassword').removeClass('alert-danger');
            empty_val = false;
        }
        if (empty_val) {
            $('#regist_modal .modal-body .empty_err').addClass('block_css');
        }else{
           $('#regist_modal .modal-body .empty_err').removeClass('block_css');
        }
        // for login check
        if (($('#regist_modal #inputLogin').val().length < 6)||($('#regist_modal #inputLogin').val().length > 32)) {
            $('#regist_modal #inputLogin').addClass('alert-danger');
            $('#regist_modal #lg_lh').addClass('block_inl');
        }else{
            $('#regist_modal #inputLogin').removeClass('alert-danger');
            $('#regist_modal #lg_lh').removeClass('block_inl');
        }
        // for password check
        if (($('#regist_modal #inputPassword').val().length < 4)||($('#regist_modal #inputPassword').val().length > 32)) {
            $('#regist_modal #inputPassword').addClass('alert-danger');
            $('#regist_modal #ps_lh').addClass('block_inl');
        }else{
            $('#regist_modal #inputPassword').removeClass('alert-danger');
            $('#regist_modal #ps_lh').removeClass('block_inl');
        }
        // for pass and confirm val
        var pass_val = $('#regist_modal #inputPassword').val();
        if ((pass_val != '')&&($('#regist_modal #confirmPassword').val() != pass_val)) {
            $('#regist_modal #confirmPassword').addClass('alert-danger');
            $('#regist_modal #ps_rptr').addClass('block_inl');
        }else if ((pass_val != '')&&($('#regist_modal #confirmPassword').val() == pass_val)){
            $('#regist_modal #confirmPassword').removeClass('alert-danger');
            $('#regist_modal #ps_rptr').removeClass('block_inl');
        }
        else{
            $('#regist_modal #confirmPassword').removeClass('alert-danger');
            $('#regist_modal #ps_rptr').removeClass('block_inl');
        }
        FW.ajax.send({
            model: 'user',
            method: 'registrate',
            data: {
              login: $('#inputLogin').val(),
              email: $('#inputEmail').val(),
              pass: $('#regist_modal #inputPassword').val(),
              confirm: $('#confirmPassword').val()
            }
        });
    });
</script>
