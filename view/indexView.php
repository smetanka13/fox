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
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-globe fa-4x" aria-hidden="true"></i></h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-shopping-cart  fa-4x" aria-hidden="true"></i></h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></li>
			<li class="main_text"><h4 class="main_title mbt au"><i class="fa fa-shopping-cart  fa-4x" aria-hidden="true"></i></h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></li>
		</ul>
	</div>
</div>
<div class="container-fluid in_bl">
	<div class="container-fluid"><h4 class="main_title">Новости</h4></div>
	<div class="wins col-xs-12 col-sm-12 col-md-6 col-lg-6" style="background-image: url('images/main/3a.jpg');">
		<a href="#"><div class="cover">Нажмите, чтобы перейти к новости</div></a>
	</div>
    <div class="wins col-xs-12 col-sm-12 col-md-6 col-lg-6" style="background-image: url('images/main/vm.jpg');">
    	<a href="#"><div class="cover">Нажмите, чтобы перейти к новости</div></a>
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
