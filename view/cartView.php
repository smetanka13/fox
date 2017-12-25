<link rel="stylesheet" type="text/css" href="css/cart.css">

<?php
	require_once 'model/categoryModel.php';
	require_once 'model/productModel.php';
?>

<div class="reg_prod col-xs-12 col-sm-5 col-md-4 col-lg-4">
	<div class="reg_prod_cnt">
		<h4 class="main_title">Оформление заказа</h4>
		<form>
			<div class="form-group" id="pay_way">
			  <select class="form-control">
			    <option>Выберите способ оплаты</option>
			    <option>1</option>
			    <option>2</option>
			    <option>3</option>
			  </select>
			</div>
			<div class="form-group" id="delivery_way">
			  <select class="form-control">
			    <option>Выберите способ доставки</option>
			    <option>1</option>
			    <option>2</option>
			    <option>3</option>
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
			<div type="submit" class="wth_boot_but confirm_but ord_maker">Оформить заказ</div>
		</form>
	</div>
</div>

<!-- БЛОК С ТОВАРАМИ -->
<div class="cart_prod cp_wth_sh col-xs-12 col-sm-7 col-md-8 col-lg-8">
	<h4 class="main_title au">На данный момент в корзине нет товаров!</h4>
	<?php

		$cookie = json_decode($_COOKIE['cart'], TRUE);
		$products = Product::selectFromDiffCategories($cookie);

		foreach($products as $index => $product) {
			$params = Category::getParams($product['category']);
	?>
	<div class="c_prod_part" data-id="<?= $index ?>">
		<div class="img-responsive c_prod_img hidden-xs cent_img"><img src="<?=
			!empty($product['image']) ?
			'catalog/'.$product['category'].'/'.$product['image'] :
			'images/icons/no_photo.svg'
		?>"></div>
		<div class="c_prod_txt">
			<a href="#"><h4 class="main_title">Xenum GPR kkkk 3 33</h4></a>
			<table class="table">
				<tbody>

					<?php
						# --- Вывод параметров товара --- #
						foreach($params as $param) {
							if(!empty($product[$param])) {
					?>

					<tr>
						<td><?= $param ?></td>
						<td class="def"><?= $product[$param] ?></td>
					</tr>

					<?php }} ?>

				</tbody>
            </table>
		</div>
		<div class="count">
			<p class="main_title">количество (шт.)</p>
			<div class="form-group">
			    <input type="number" onchange="Cart.updateQuantity(<?= $index ?>, $(this).val())" class="form-control" value="<?= $cookie[$index]['quantity'] ?>"  min="1" >
			</div>
		</div>
		<div class="c_price">
			<p class="main_title"><?= $product['price'] * $cookie[$index]['quantity'] ?> &euro; (<?= $product['price'] ?> &euro;)</p>
			<a href="product?category=<?= $product['category'] ?>&id=<?= $product['id_prod'] ?>">
				<button class="wth_boot_but confirm_but">Подробнее</button>
			</a>
		</div>
		<img onclick="Cart.remove(<?= $index ?>, deleteProduct)" class="close_img" src="images/icons/close.svg">
	</div>
	<?php } ?>
</div>

<!-- FOR ORDER MODAL -->
<div id="order_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">
			Ваш заказ принят!
			<?php if(User::logged()) echo '<br>Подробнее вы можете просмотреть в личном кабинете!' ?>
		</h4>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$('#phone input').mask("+38 (099) 999-99-99", {autoclear: false});

		// ДЛЯ ОПОВЕЩЕНИЯ О ПРИНЯТИИ ЗАКАЗА
		$('.ord_maker').click(function(){
			ajaxController({
				model: 'order',
				method: 'add',
				callback: function(data) {
					if(data.status) {
						$('#order_modal').modal('show');
						setTimeout(function(){
							$('#order_modal').modal('hide');
						}, 2000);
					} else {
						// data.error - текст ошибки
						// Cart.empty();
					}
				},
				pay_way: $('#pay_way select').val(),
				delivery_way: $('#delivery_way select').val(),
				public: $('#public input').val(),
				city: $('#city input').val(),
				address: $('#address input').val(),
				email: $('#email input').val(),
				phone: $('#phone input').val(),
				text: $('#text textarea').val()
			});
		});
	});

	function deleteProduct(index) {
		Cart.updateVisual();
		$('.c_prod_part[data-id='+index+']').remove();
	}

</script>
