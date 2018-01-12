<link rel="stylesheet" type="text/css" href="css/index.css">
<link rel="stylesheet" type="text/css" href="bower_components/slick-carousel/slick/slick.css">

<div id="slider_cnt">
	<?php

        $items = array_diff(scandir('./images/slider'), array('..', '.'));

        foreach($items as $item) {
    ?>
	<div style="background-image: url(images/slider/<?= $item ?>)"></div>
	<?php } ?>
</div>

<div id="logo_cnt">
	<div class="img_cnt">
		<div id="s_lf" style="background-image: url('images/icons/lar.png');"></div>
		<div id="s_rg" style="background-image: url('images/icons/rar.png');"></div>
		<div class="sl_vp">
			<ul class="list-inline">
				<li title="Перейти"><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li title="Перейти"><a href="https://www.motul.com/ua/uk"><div><img src="images/logos/motul.png"></div></a></li>
				<li title="Перейти"><a href="http://www.idemitsu.com/"><div><img src="images/logos/idemitsu.png"></div></a></li>
				<li title="Перейти"><a href="https://www.castrol.com/ru_ua/ukraine.html"><div><img src="images/logos/castrol.png"></div></a></li>
				<li title="Перейти"><a href="http://www.ligaco.com/"><div><img src="images/logos/agip.jpg"></div></a></li>
				<li title="Перейти"><a href="http://www.shell.ua"><div><img src="images/logos/shell.png"></div></a></li>
				<li title="Перейти"><a href="http://www.total.ua/"><div><img src="images/logos/total.png"></div></a></li>
				<li title="Перейти"><a href="https://xado.ua/"><div><img src="images/logos/xado.png"></div></a></li>
				<li title="Перейти"><a href="https://xenum.ua"><div><img src="images/logos/xenum.jpg"></div></a></li>
			</ul>
		</div>
	</div>
</div>

<?php require_once 'view/layout/tops.php'; ?>

<div class="container-fluid ind_ab ptb">
	<h4 class="main_title pbm">Почему именно FoxMotors?</h4>
	<div class="container ind_ab_cnt">
		<ul class="container-fluid list-inline ">
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-money fa-4x" aria-hidden="true"></i></h4>
			<p>Цены на автотовары в нашем интернет-магазине всегда актуальны. Относительно невысокая стоимость товаров в нашем интернет-магазине связана с тем, что мы ориентированы на большие объемы розничных продаж и сотрудничество с оптовыми покупателями.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa  fa-check-square-o fa-4x" aria-hidden="true"></i></h4>
			<p>Вся линейка товаров нашего интернет-магазина имеет сертификаты соответствия качества. Кроме того, Fox Motors – авторизованная точка продаж. Покупая автотовары в Fox Motors – вы можете быть уверены в качестве продукции.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-globe fa-4x" aria-hidden="true"></i></h4>
			<p>Доставка возможна по всей Украине любым удобным способом. Мы рекомендуем пользоваться услугами крупнейшего оператора Украины по экспресс-доставке товаров – ООО «Нова пошта». Если стоимость заказа превышает 2000 грн. – доставка бесплатная.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-child fa-4x" aria-hidden="true"></i></h4>
			<p>Купить товары для авто у нас, значит , что вы можете быть уверены в грамотном и вежливом обслуживании, быстроте и точности исполнения заказов. Интернет-магазин Fox Motors ценит своих клиентов и будет рад видеть вас и ваш автомобиль или мотоцикл в качестве постоянных клиентов на долгие годы!</p></li>
		</ul>
	</div>
</div>
<div class="container-fluid in_bl">
	<div class="hidden-xs hidden-sm inl_ttl"><h4 class="main_title">Новости <i class="fa fa-arrow-down" aria-hidden="true"></i></h4><h4 class="main_title">Подбор масла <i class="fa fa-arrow-down" aria-hidden="true"></i></h4></div>
	<div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><h4 class="main_title">Новости <i class="fa fa-arrow-down" aria-hidden="true"></i></h4></div>
	<div class="wins col-xs-12 col-sm-12 col-md-6 col-lg-6" style="background-image: url('images/main/3a.jpg');">
		<a href="blog"><div class="cover">Нажмите, чтобы перейти к новостям</div></a>
	</div>
	<div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><h4 class="main_title">Подбор масла <i class="fa fa-arrow-down" aria-hidden="true"></i></h4></div>
    <div class="wins col-xs-12 col-sm-12 col-md-6 col-lg-6" style="background-image: url('images/main/vm.jpg');">
    	<a href="pick"><div class="cover">Нажмите, чтобы перейти к подбору</div></a>
    </div>
</div>

<script src="js/slide.js" ></script>
<script>
		$(document).ready(function() {

			var rightArr = $('#s_rg');
			var leftArr = $('#s_lf');
			var contSl = $('.img_cnt ul');
			var offset = 0;
			var n=5;//кол-во блоков в строке
			var maxOffset = (Math.ceil($('.img_cnt ul li').length / n)-1)*100;

			// $('.img_cnt ul li').length;
			rightArr.click(function(){
				if (offset >= maxOffset) return;
				offset += 100;
				contSl.css('left','-' + offset + '%');
			});

			leftArr.click(function(){
				if (offset <= 0) return;
				offset -= 100;
				contSl.css('left','-' + offset + '%');
			});

			/* --- SLIDER SENSOR --- */
			var size = window.innerWidth;
			$('#slider_cnt').slick({
				speed: 200,
				arrows: false,
				autoplay: true,
				infinite: true
			});
		});
</script>
