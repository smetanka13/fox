<link rel="stylesheet" type="text/css" href="css/index.css">
<link rel="stylesheet" type="text/css" href="css/slick.css">

<div id="slider_cnt">
	<div style="background-image: url(images/slider/qwe.jpeg)"></div>
	<div style="background-image: url(images/slider/asd.jpg)"></div>
	<div style="background-image: url(images/slider/zxc.jpg)"></div>
</div>

<div id="logo_cnt">
	<div class="img_cnt">
		<div id="s_lf" style="background-image: url('images/icons/lar.png');"></div>
		<div id="s_rg" style="background-image: url('images/icons/rar.png');"></div>
		<div class="sl_vp">
			<ul class="list-inline">
				<li><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/motul.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/idemitsu.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/castrol.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/motul.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/idemitsu.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/castrol.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/idemitsu.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/castrol.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/motul.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/elf.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/idemitsu.png"></div></a></li>
				<li><a href="http://www.elf.ua"><div><img src="images/logos/castrol.png"></div></a></li>
			</ul>
		</div>
	</div>
</div>

<?php require_once 'view/layout/tops.php'; ?>

<script type="text/javascript" src="js/slide.js" ></script>
<script type="text/javascript" src="js/addons/slick.min.js"></script>

<script type="text/javascript">
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

<!-- <script type="text/javascript">
	$(document).ready(function(){
		$('#product_block').addClass("hidden_css").viewportChecker({
		  classToAdd: 'visible_css animated fadeInDown',
	      classToRemove: 'hidden_css',
	      offset: [10%],
	      repeat: true
		});
	});
</script> -->
