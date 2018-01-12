<link rel="stylesheet" href="css/card_prod_card.css">
<link rel="stylesheet" type="text/css" href="css/product_block.css">
<link rel="stylesheet" type="text/css" href="css/tops.css">

<div class="container-fluid pr_bck">
	<div class="pr_main_crd container">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<a href="<?=!empty($_DATA['prod']['image']) ?'material/catalog/'.$_DATA['prod']['category'].'/'.$_DATA['prod']['image'] :'images/icons/no_photo.svg'
			?>" data-lightbox="image-1">
				<div class="pr_img cent_img">
						<img src="<?=!empty($_DATA['prod']['image']) ?'material/catalog/'.$_DATA['prod']['category'].'/'.$_DATA['prod']['image'] :
						'images/icons/no_photo.svg'?>">
						<span class="discount_deg badge badge-pill badge-danger">-25%</span>
				</div>
			</a>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 pr_description">
			<ul class="list-unstyled">
				<li class="pr_name"><?= $_DATA['prod']['title'] ?></li>
				<li class="code_name">Код товара : <?= $_DATA['prod']['articule'] ?></li>
				<li>Цена : <b><?= $_DATA['prod']['price'] ?></b> &euro;<span id="sec_price"><div></div><?= $_DATA['prod']['price'] ?></b> &euro;</span></li>
				<li><a href="delivery"><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i> Условия доставки</a></li>
				<li><a href="#"><i class="fa fa-youtube-play fa-lg fa-fw" aria-hidden="true"></i> Видео на YouTube</a></li>
				<li><span id="like"><i class="fa fa-heart fa-lg fa-fw" aria-hidden="true"></i> В избранное</span></li>
				<?php
					if($_GET['category'] == 'Масла') {

						$amount = Category::getValues('Масла', 'Литраж');

						$other = Search::find(
							0,
							str_replace($amount, '', $_DATA['prod']['title']),
							'Масла'
						);

						if($other['found'] > 1) {
				?>
				<li>
					<?php
						foreach ($other['search_result'] as $found) {
							$url = 'product?category=Масла&id=' . $found['id_prod'];
							if($found['id_prod'] == $_DATA['prod']['id_prod']) continue;
					?>
						<a href="<?= $url ?>"><?= $found['params']['Литраж'] ?></a>
					<?php } ?>
				</li>
				<?php }} ?>
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

						foreach($_DATA['prod']['params'] as $param => $val) {
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

<?php if(!empty($_DATA['prod']['text'])) { // Если пустой текст , то оно не выводит ?>
<div class="pr_about">
	<h4 class="main_title">Описание :</h4>
	<div class="main_text pr_about_txt">
		<?= $_DATA['prod']['text'] ?>
	</div>
</div>
<?php } ?>

<div class="top_wrapper">
	<div class="top_cnt">
		<div class="top_header">ПОХОЖИЕ ТОВАРЫ</div>
		<div class="top_viewport">
			<div class="top_slider"></div>
		</div>
	</div>
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

<!-- FOR LIKE MODAL -->
<div id="like_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content siglog_window">
      <div class="modal-header">
        <h4 class="modal-title main_title">Товар добавлен в избранное !</h4>
      </div>
    </div>
  </div>
</div>

<script>

	$(document).ready(function() {

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

		var related_data = <?= $_DATA['related'] ?>;

		for(let i in related_data) {
			$('.top_cnt .top_slider').append(prodBlock(related_data[i]));
		}

	});

	// для избранного
	$('.pr_description #like').click(function() {
		$('#like_modal').modal('show');
		setTimeout(function(){
			$('#like_modal').modal('hide');
		}, 1000);
	});
	// для скидки
	// if () {
	//   $('#sec_price').addClass(block_css);
	// 	$('.discount_deg').addClass(block_css);
	// }else{
	// 	$('#sec_price').removeClass(block_css);
	// 	$('.discount_deg').removeClass(block_css);
	// }
</script>
<script src="js/tops.js"></script>
