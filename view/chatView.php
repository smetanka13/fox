<link rel="stylesheet" type="text/css" href="css/chat.css">
<link rel="stylesheet" href="css/about_same.css">

<div class="layout_block">
	<textarea class="opinion_box" autofocus="autofocus"></textarea>
	<div class="opinion_box_bottom">
		<div class="rating">
			<svg class="stars" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/></g></svg>
			<svg class="stars" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/></g></svg>
			<svg class="stars" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/></g></svg>
			<svg class="stars" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/></g></svg>
			<svg class="stars" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/></g></svg>
		</div>
		<div class="leave_opin">
			<button class="buttons" onclick="opinionSend()">Оставить отзыв</button>
		</div>
	</div>
	<div class="opinion_war">Ваш отзыв отправлен и находится на рассмотрении у модератора.</div>

	<!-- При попытке отправить пустой отзыв -->
	<div class="opinion_war_not">Пожалуйста , поставьте оценку товару.</div>

	<!-- Если пользователь на зарегистрирован -->
	<?php
		if(!$User->logged())
			echo '
				<div class="opinion_war_name">
					<p>Чтобы оставить отзыв введите ваше имя.</p>
					<div class="var_send_name">
			 			<input placeholder="Введите ваше имя">
			 		</div>
				</div>
			';
	?>

</div>

<!-- Далее блоки с коментариями остальных пользователей -->
<?php
	$ops = Main::select("
		SELECT * FROM `chat`
		WHERE `confirmed` = '1'
	", TRUE);
	for($i = 0; isset($ops[$i]); $i++) {
?>
<div class="layout_block after_opinion" id="op_<?= $ops[$i]['id'] ?>">
	<div class="pers_opin_block">
		<div class="ava_opin_block">
			<div class="user_name">
				<h3><?= $ops[$i]['name'] ?></h3>
			</div>
		</div>
		<div class="text_opin_block">
			<div class="opin_txt">
				<p><?= $ops[$i]['text'] ?></p>
			</div>
		</div>
		<div class="rating_opin_block">
			<div class="rating_opin">';
			<?php
				for($j = 1; $j <= 5; $j++) {

					if($j <= $ops[$i]['mark']) $color = "#F7931E";
					else $color = "#212121";

					echo '
						<svg class="stars_after" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 19.481 19.481" enable-background="new 0 0 19.481 19.481" width="512px" height="512px"><g><path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z" fill="'.$color.'" /></g></svg>
					';
				}
			?>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php
	if(count($ops) > 10)
		echo '
			<div class="line_noy line_opin_stripe">
				<button class="buttons more_opinions">Больше отзывов</button>
			</div>
		';
?>

<script type="text/javascript">

	function opinionSend()
	{

		ajaxController({
			listener: 'chat',
			callback: chatCallBack,
			text: $('.opinion_box').val(),
			mark: $('.rating').attr('data-star'),  // ОЦЕНКА (ЗВЕЗДЫ)
			<?php
				if(!$User->logged())
					# --- СЮДА ВСТАВЬ БЛОК С ИМЕНЕМ --- #
					echo "name: $('opinion_war_name input').val()";
				else
					echo "name: ".$User->get('public');
			?>
		});

		// Удаляет содержимое отзыва по нажатию кнопки,убрать при работе этого класса opinion_war_name
		// $('.opinion_box').val('');
	}

	function chatCallBack(json) {
		var data = JSON.parse(json);

		if(data.status == false) {
			if(typeof(data.system) != 'undefined') {
				sysErr('Системная ошибка , попробуйте позже');
			}
			if(typeof(data.mark) != 'undefined') {
				$('.rating').animateCss('shake');
		    }
		} else {
			$('.opinion_war').show();
			$('.opinion_box').val('');
		}
	}

	$(document).ready(function() {
		$('.after_opinion').addClass("hidden").viewportChecker({
            classToAdd:'visible animated fadeIn',
		});
		$('.line_opin_stripe').addClass("hidden").viewportChecker({
            classToAdd:'visible animated fadeIn',
		});
	});

</script>

<script type="text/javascript" src='js/stars.js'></script>
