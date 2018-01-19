<link rel="stylesheet" type="text/css" href="css/personal_room.css">

<div class="container pr_main au btm">
	<h4 class="main_title tl_mg"></h4>
	<ul class="nav nav-tabs pr_tabs">
    <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-shopping-cart fa-fw" aria-hidden="true"></i> Покупки</a></li>
		<li><a data-toggle="tab" href="#menu4"><i class="fa fa-check-square fa-fw" aria-hidden="true"></i> Ожидается подтверждение</a></li>
		<li><a data-toggle="tab" href="#menu3"><i class="fa fa-heart fa-fw" aria-hidden="true"></i> Избранное</a></li>
    <li><a data-toggle="tab" href="#menu2"><i class="fa fa-user fa-fw" aria-hidden="true"></i> Личные данные</a></li>
  </ul>
    <div class="tab-content">
		<div id="home" class="tab-pane fade in active">
	      <h4 class="pr_titles_cat">Ваши покупки</h4>
	      <p>Тут вы может увидеть список подтвержденных заказов.</p>
		    <div class="tb_cnt">
			    <table id="tb_buy" class="table tb_buy table-bordered">
					<thead>
						<tr>
							<th>Номер заказа</th>
							<th>Название</th>
							<th>Количество, шт</th>
							<th>Сумма, грн</th>
							<th>Способ оплаты</th>
							<th>Способ доставки</th>
							<th>Дата</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
		<div id="menu4" class="tab-pane fade">
			 <h4 class="pr_titles_cat">Ожидается подтверждение.</h4>
			 <p>После подтверждения заказа Вам перезвонит администратор.</p>
				 <div class="tb_cnt">
					 <table id="tb_bf_buy" class="table tb_buy table-bordered">
					 <thead>
						 <tr>
							 <th>Номер заказа</th>
							 <th>Названия товаров</th>
							 <th>Количество, шт</th>
							 <th>Сумма, грн</th>
							 <th>Способ оплаты</th>
							 <th>Способ доставки</th>
							 <th>Дата</th>
						 </tr>
					 </thead>
					 <tbody></tbody>
				 </table>
			 </div>
		 </div>
		<div id="menu3" class="tab-pane fade">
			 <h4 class="pr_titles_cat">Избранные товары.</h4>
			 <p>Тут вы можете перейти на страницу товара щелкнув на название.</p>
				 <div class="tb_cnt">
					 <table id="favourite_tb" class="table tb_buy table-bordered">
					 <thead>
						 <tr>
						 <th>Удалить</th>
						 <th>Название</th>
						 <th>Цена</th>
						 </tr>
					 </thead>
					 <tbody></tbody>
				 </table>
			 </div>
		 </div>
		 <div id="menu2" class="tab-pane fade">
	      <h4 class="pr_titles_cat">Ваши личные параметры.</h4>
	      <p>Тут вы можете редактировать параметры аккаунта.</p>
		      <form role="form" class="pr_inf_cnt">
		      	<div class="form-group pr_edit_acc">
				    <label><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> Изменить ФИО</label>
				    <div onclick="pInfEd(4)" class="wth_boot_but confirm_but pr_edit_memor_pers4 ed_pr_btt">Изменить</div>
				    <span id="pr_ed_inf4">
					    <input type="text" class="form-control" placeholder="Введите Ваше ФИО">
					    <div onclick="pInfNEd(4)" class="wth_boot_but confirm_but pr_noedit_memor4">Отменить</div>
					    <div onclick="pInSV(4)" class="wth_boot_but confirm_but pr_edit_memor ed_m_4">Сохранить</div>
				    </span>
				</div>
		      	<div class="form-group pr_edit_acc">
				    <label><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> Ваш логин : USER</label>
				    <div onclick="pInfEd(1)" class="wth_boot_but confirm_but pr_edit_memor_pers1 ed_pr_btt">Изменить</div>
				    <span id="pr_ed_inf1">
					    <input type="text" class="form-control" placeholder="Введите новый логин">
					    <div onclick="pInfNEd(1)" class="wth_boot_but confirm_but pr_noedit_memor1">Отменить</div>
					    <div onclick="pInSV(1)" class="wth_boot_but confirm_but pr_edit_memor ed_m_1">Сохранить</div>
				    </span>
				</div>
				<div class="form-group pr_edit_acc">
				    <label><i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i> Изменить пароль</label>
				    <div onclick="pInfEd(2)" class="wth_boot_but confirm_but pr_edit_memor_pers2 ed_pr_btt">Изменить</div>
				    <span id="pr_ed_inf2">
					    <input type="password" class="form-control" placeholder="Введите пароль">
					    <input type="password" class="form-control" placeholder="Повторите пароль">
					    <div onclick="pInfNEd(2)" class="wth_boot_but confirm_but pr_noedit_memor2">Отменить</div>
					    <div onclick="pInSV(2)" class="wth_boot_but confirm_but pr_edit_memor ed_m_2">Сохранить</div>
				    </span>
				</div>
				<div class="form-group pr_edit_acc">
				    <label><i class="fa fa-phone-square fa-lg fa-fw" aria-hidden="true"></i> Изменить телефон</label>
				    <div onclick="pInfEd(3)" class="wth_boot_but confirm_but pr_edit_memor_pers3 ed_pr_btt">Изменить</div>
				    <span id="pr_ed_inf3">
					    <input type="tel" class="form-control" placeholder="Введите новый телефон">
					    <div onclick="pInfNEd(3)" class="wth_boot_but confirm_but pr_noedit_memor3">Отменить</div>
					    <div onclick="pInSV(3)" class="wth_boot_but confirm_but pr_edit_memor ed_m_3">Сохранить</div>
				    </span>
				</div>
			</form>
			<label class="lgo_lb">Покинуть аккаунт?</label>
			<div data-toggle="modal" data-target="#logout_bar" class="btn confirm_but"><i class="fa fa-sign-out fa-lg fa-fw" aria-hidden="true"></i> Выйти с аккаунта</div>
	    </div>
	</div>
</div>

<!-- FOR DATA MODAL -->
<div id="data_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">Ваши данные изменены !</h4>
      </div>
    </div>
  </div>
</div>

<script src="js/personal.js" charset="utf-8"></script>
<script type="text/javascript">
	function pInfEd(num){

		$('.pr_edit_memor_pers' + num).css('display','none');
		$('#pr_ed_inf' + num).css('display','block');
	};
	function pInfNEd(num){

		$('.pr_edit_memor_pers' + num).css('display','block');
		$('#pr_ed_inf' + num).css('display','none');
	};

	function pInSV(num){

		$('.pr_edit_memor_pers' + num).css('display','block');
		$('#pr_ed_inf' + num).css('display','none');
	};

	// ДЛЯ ОПОВЕЩЕНИЯ ОБ ИЗМЕНЕНИИ ДАННЫХ
	$('.pr_edit_memor').click(function(){
		$('#data_modal').modal('show');
		setTimeout(function(){
			$('#data_modal').modal('hide');
		}, 1000);
	});
</script>
