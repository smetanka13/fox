<?php
	# --- Это не трогай, это я беру из БД товар по категории и айди из адреса --- #
	# --- < ?=  ? > Этот тег используется для вывода переменной PHP --- #

	require_once 'model/productModel.php';

	$product = Product::getById($_GET['category'], $_GET['id']);
?>
<link rel="stylesheet" href="css/card_prod_card.css">
<div class="container-fluid pr_bck">
	<div class="pr_main_crd container">

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<a href="<?=!empty($product['image']) ?'catalog/'.$product['category'].'/'.$product['image'] :'images/icons/no_photo.svg'
			?>" data-lightbox="image-1">
				<div class="pr_img cent_img"><img src="<?=
				!empty($product['image']) ?
				'material/catalog/'.$product['category'].'/'.$product['image'] :
				'images/icons/no_photo.svg'
			?>"></div>
			</a>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 pr_description">
			<ul class="list-unstyled">
				<li class="pr_name"><?= $product['title'] ?></li>
				<li class="code_name">Код товара : <?= $product['articule'] ?></li>
				<li>Цена : <b><?= $product['price'] ?></b> &euro;</li>
				<li><a href="delivery"><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i> Условия доставки</a></li>
				<li><a href="#"><i class="fa fa-youtube-play fa-lg fa-fw" aria-hidden="true"></i> Видео на YouTube</a></li>
				<li><select>
					<option>Выберите литраж</option>
					<option>1л</option>
					<option>3л</option>
					<option>5л</option>
					<option>10л</option>
				</select></li>
				<li><input type="number" class="form-control" value="1"  min="1" ></li>
				<li><button class="pr_card_btn wth_boot_but confirm_but">В корзину</button></li>
			</ul>
		</div>

		<div class="hidden-xs hidden-sm col-md-4 col-lg-4 pr_short_desc">
			<div class="sh_desc_scroll">
				<p class="main_title">Характерстики товара</p>
				<dl class="dl-horizontal">
					<?php
						# --- Вывод параметров --- #

						foreach($product['params'] as $param => $val) {
							if(!empty($val))
								echo "
									<dt>$param</dt>
									<dd>$val</dd>
								";
						}
					?>
				</dl>
			</div>
		</div>
	</div>
</div>

<?php if(!empty($product['text'])) { // Если пустой текст , то оно не выводит ?>
<div class="pr_about">
	<h4 class="main_title">Описание :</h4>
	<div class=".main_text pr_about_txt">
		<?= $product['text'] ?>
	</div>
</div>
<?php } ?>

<!-- ДЛЯ "ПОХОЖИЕ ТОВАРЫ" -->
<div class="container">
	ДЛЯ "ПОХОЖИЕ ТОВАРЫ"
</div>

<!-- FOR CART MODAL -->
<div id="cart_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">Товар добавлен в корзину !</h4>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$('.pr_description ul li button').click(function() {
		Cart.add(
			<?= $_GET['id'].',\''.$_GET['category'].'\'' ?>,
			$('.pr_description ul li input').val(),
			function() {
				$('#cart_modal').modal('show');
				setTimeout(function(){
					$('#cart_modal').modal('hide');
				}, 1000);
			}
		)
	});
</script>
