<link rel="stylesheet" type="text/css" href="css/cart.css">

<?php require_once 'model/productModel.php' ?>

<div class="reg_prod col-xs-12 col-sm-12 col-md-4 col-lg-4">
	<div class="reg_prod_cnt">
		<h4 class="main_title">Оформление заказа</h4>
		<form>
			<div class="form-group" id="pay_way">
			  <select class="form-control">
			    <option>Выберите способ оплаты</option>
			    <option>Оплата банковской картой</option>
			  </select>
			</div>
			<div class="form-group" id="delivery_way">
			  <select class="form-control">
			    <option>Выберите способ доставки</option>
			    <option>Самовывоз</option>
			    <option>Новая почта</option>
			  </select>
			</div>
			<div class="form-group" id="public">
			    <label>ФИО:</label>
			    <input value="<?php if(User::logged()) echo User::get("public") ?>" type="text" class="form-control" placeholder="Введите ФИО">
			</div>
			<div class="form-group" id="city">
			    <label>Город:</label>
			    <input type="text" class="form-control" placeholder="Введите город">
			</div>
			<div class="form-group" id="address">
			    <label>Адрес:</label>
			    <input type="text" class="form-control" placeholder="Введите адрес">
			</div>
			<div class="form-group" id="email">
			    <label>Email:</label>
			    <input value="<?php if(User::logged()) echo User::get("email") ?>" type="email" class="form-control" placeholder="Введите email">
			</div>
			<div class="form-group" id="phone">
			    <label>Телефон:</label>
			    <input value="<?php if(User::logged()) echo User::get("phone") ?>" type="tel" class="form-control" placeholder="Введите телефон">
			</div>
			<div class="form-group" id="text">
			  <textarea class="form-control" rows="5" placeholder="Комментарий к заказу"></textarea>
			</div>
			<div  type="submit" class="wth_boot_but confirm_but ord_maker">Оформить заказ</div>
		</form>
	</div>
</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="cart_prod ptb cp_wth_sh col-xs-12 col-sm-12 col-md-8 col-lg-8">
	<?php if(empty($_COOKIE['cart'])) { ?>
	<h4 class="main_title au">На данный момент в корзине нет товаров!</h4>
	<?php } ?>
	<button id="trash_but" data-toggle="tooltip" data-placement="auto left" title="Нажмите, чтобы очистить коризину" class="wth_boot_but confirm_but"><i class="fa fa-trash fa-2x " aria-hidden="true"></i></button>
	<?php

		$cookie = json_decode($_COOKIE['cart'], TRUE);
		$keys = array_keys($cookie);
		$cookie = array_values($cookie);
		$products = Product::selectFromDiffCategories($cookie);

		foreach($products as $index => $product) {

			$image = !empty($product['image']) ? 'material/catalog/'.$product['category'].'/'.$product['image'] : 'images/icons/no_photo.svg';
	?>
	<div class="c_prod_part" data-key="<?= $keys[$index] ?>">
		<div class="img-responsive c_prod_img hidden-xs cent_img"><img src="<?= $image ?>"></div>
		<div class="c_prod_txt">
			<a href="product?category=<?= $product['category'] ?>&id=<?= $product['id_prod'] ?>"><h4 class="main_title"><?= $product['title'] ?></h4></a>
		</div>
		<div class="count">
			<p class="main_title">количество (шт.)</p>
			<div class="form-group">
			    <input type="number" class="form-control" value="<?= $cookie[$index]['quantity'] ?>"  min="1">
			</div>
		</div>
		<div class="c_price">
			<p class="main_title"><?= $product['price'] ?> &euro;</p>
			<a href="product?category=<?= $product['category'] ?>&id=<?= $product['id_prod'] ?>">
				<button class="wth_boot_but confirm_but">Подробнее</button>
			</a>
		</div>
		<img class="close_img" src="images/icons/close.svg">
	</div>
	<?php } ?>
</div>

<!-- FOR ORDER MODAL -->
<div id="order_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">Ваш заказ принят!
			<?php if(User::logged()) { ?>

			<br>Подробнее вы можете просмотреть в личном кабинете!

			<?php } ?>
		</h4>
      </div>
    </div>
  </div>
</div>
<!-- FOR ERROR ORDER MODAL -->
<div id="err_order_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window err_modal">
      <div class="modal-header">
        <h4 class="modal-title main_title">Пожалуйста, заполните все поля верно !</h4>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.c_prod_part .close_img').click(function() {
			var that = this;
			var key = $(that).parent().attr('data-key');
			Cart.remove(key, function() {
				$(that).parent().remove();
			});
		});

		$('.c_prod_part input[type=number]').change(function() {
			var key = $(this).parent().parent().parent().attr('data-key');
			Cart.updateQuantity(key, $(this).val());
		});

		$('#phone input').mask("+38 (099) 999-99-99", {autoclear: false});

		// ДЛЯ ОПОВЕЩЕНИЯ О ПРИНЯТИИ ЗАКАЗА
		$('.ord_maker').click(function(){
			FW.ajax.send({
				model: 'order',
				method: 'add',
				callback: function(data) {
					if(data.status) {
						$('#order_modal').modal('show');
						setTimeout(function(){
							$('#order_modal').modal('hide');
						}, 2000);
						Cart.empty();
						location.href = "/?msg=Заказ принят!";
					} else {
						// data.error - текст ошибки
						// Cart.empty();
						$('#err_order_modal').modal('show');
						setTimeout(function(){
							$('#err_order_modal').modal('hide');
						}, 2000);
					}
				},
				data: {
					pay_way: $('#pay_way select').val(),
					delivery_way: $('#delivery_way select').val(),
					public: $('#public input').val(),
					city: $('#city input').val(),
					address: $('#address input').val(),
					email: $('#email input').val(),
					phone: $('#phone input').val(),
					text: $('#text textarea').val()
				}
			});
		});
		$('.cart_prod #trash_but').click(function(){
			Cart.empty();
			$('.cart_prod').empty();
			$('.cart_prod').append('<h4 class="main_title au">На данный момент в корзине нет товаров!</h4>');
		});
	});
</script>
