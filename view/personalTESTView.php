<link rel="stylesheet" href="css/personal_room.css">
<div class="pw_main">
	<div class="layout_block">
		<h3>Личный Кабинет</h3>
		<div class="personal_info">
			<table>
				<tr>
					<td>Email:</td>
					<td><?= $User->get('email') ?></td>
				</tr>
				<tr>
					<td>ФИО:</td>
					<td id="user_public"><?= $User->get('public') ?></td>
				</tr>
				<tr>
					<td>Номер мобильного:</td>
					<td id="user_phone"><?= $User->get('phone') ?></td>
				</tr>
					<tr>
					<td><button class="buttons" onclick="roomEdit()" class="remark" >Изменить парамеры</button></td>
				</tr>
			</table>
		</div>

		<div class="personal_info_edit">
			<table>
				<tr>
					<td>Пароль:</td>
					<td><input id="ed_inp_pass" class="inf_ed_inp" type="password" placeholder="Введите новый пароль"></td>
				</tr>
				<tr>
					<td>*Подтвердите пароль</td>
					<td><input id="ed_inp_conf" class="inf_ed_inp" type="password" placeholder="Подтвердите пароль"></td>
				</tr>
				<tr>
					<td>ФИО:</td>
					<td><input id='ed_inp_public' class="inf_ed_inp" type="text" placeholder="Введите ФИО"></td>
				</tr>
				<tr>
					<td>Номер мобильного:</td>
					<td><input id="ed_inp_phone" class="inf_ed_inp" type="tel" placeholder="Введите новый номер"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="buttons but_edit" onclick="ajaxController({
							listener:'edituser',
							callback: save,
							pass:$('#ed_inp_pass').val(),
							confirm:$('#ed_inp_conf').val(),
							public:$('#ed_inp_public').val(),
							phone:$('#ed_inp_phone').val()
						})">Сохранить</button>
						<button class="buttons but_edit" onclick="undo()">Отменить</button>
					</td>
				</tr>
			</table>
		</div>

		<div class="order_table">
			<div class="p_ord_but">
				<button id="ord_but_1">Заказы</button>
				<!-- <button id="ord_but_2">Скидки</button> -->
			</div>

		<table class="p_ord_all">
		<thead>
			<tr class="p_ord_title">
			    <td>№</td>
			    <td>Дата</td>
			    <td>Перечень товаров</td>
			    <td>Сумма</td>
			    <td>Статус</td>
		  	</tr>
		</thead>
		<tbody>
			<tr>
			    <td>4444</td>
			    <td>55.65.45</td>
			    <td>Xenum bla-bla</td>
			    <td>1000000$</td>
			    <td class="p_avaliable">В наличии</td>
		  	</tr>
		  	<tr>
			    <td>4444</td>
			    <td>55.65.45</td>
			    <td>Xenum bla-bla</td>
			    <td>1000000$</td>
			    <td class="p_avaliable">В наличии</td>
		  	</tr>
		  	<tr>
			    <td>4444</td>
			    <td>55.65.45</td>
			    <td>Xenum bla-bla</td>
			    <td>1000000$</td>
			    <td class="p_avaliable">В наличии</td>
		  	</tr>
		</tbody>
		</table>
		</div>

	</div>
</div>


<script type="text/javascript" src="js/personal.js"></script>
<script>
	$( document ).ready(function() {
		getUnaccepted();
		getAccepted();
		getFavorite();
		setInterval(getUnaccepted,10000);
		setInterval(getAccepted,20000);
		setInterval(getFavorite,10000);
	});
</script>
