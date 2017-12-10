<link rel="stylesheet" type="text/css" href="css/callback.css">

<!-- raty -->
<link rel="stylesheet" href="library/raty-master/lib/jquery.raty.css">
<script src="library/raty-master/lib/jquery.raty.js"></script>

<div class="main_cont_cnt content_cnt">
	<div class="container-fluid com_bt">
		<button data-toggle='modal' data-target='#callb_modal' class="btn confirm_but"><i class="fa fa-pencil-square-o fa-lg fa-fw" aria-hidden="true"></i> Оставить отзыв</button>
	</div>
	<div class="container-fluid">
		<div class="com_bl">
			<div class="fst_com">
				<ul class="list-unstyled">
					<li>Покупатель</li>
					<li><div class="cal_img"></div>20.10.2017</li>
					<li><div class="c_stars"></div></li>
				</ul>
			</div>
			<div class="sc_com main_text">
				<p>Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!Отличный магазин!</p>
				<div class="read_more">Читать полностью...</div>
				<div class="read_less">Скрыть...</div>
			</div>
		</div>

		<div class="com_bl">
			<div class="fst_com">
				<ul class="list-unstyled">
					<li>Покупатель</li>
					<li><div class="cal_img"></div>20.10.2017</li>
					<li><div class="c_stars"></div></li>
				</ul>
			</div>
			<div class="sc_com main_text">
				<p>KEKEKEKEKEKEKEK</p>
				<div class="read_more">Читать полностью...</div>
				<div class="read_less">Скрыть...</div>
			</div>
		</div>
	</div>
</div>

<!-- FOR callback -->
<div id="callb_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content siglog_window">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">X</button>
				<h4 class="modal-title main_title"><i class="fa fa-pencil-square-o fa-lg fa-fw" aria-hidden="true"></i> Оставьте Ваш отзыв</h4>
			</div>
			<div class="modal-body">
				<form class="enter_reg_place" >
					  <div class="form-group">
					  	<div class="form-group">
					    	<label for="inputText" class="enter_reg_lable">Имя:</label>
					    	<input type="text" class="form-control" id="" name="login" placeholder="Введите логин">
					  	</div>
					    	<label for="inputEmail" class="enter_reg_lable">Email:</label>
					    	<input type="email" class="form-control" id="inputEmail" name="mail" placeholder="Введите email">
					  </div>
					  <div class="form-group">
					  	<label>Оцените наши услуги</label>
					  	<div class="cm_stars"></div>
					  </div>
					  <div class="form-group">
					  	<label>Отзыв</label>
					  	<textarea class="com_txa"></textarea>
					  </div>
					  <div class="form-group plms">
					  	<p>Добавить плюсы и минусы<!-- <div class="caret cr_pl"></div> --></p>
					  	<label>Плюсы</label>
					  	<textarea class="com_txa"></textarea>
					  	<label>Минусы</label>
					  	<textarea class="com_txa"></textarea>
					  </div>
					  <div class="form-group">
					  	<button class="wth_boot_but confirm_but">Добавить отзыв</button>
					  </div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// jQuery Raty - A Star Rating Plugin
	$('.c_stars').raty({ starType: 'i' });
	$('.c_stars').raty('score', 4); //сколько изначально выбрано;
	// in modal
	$('.cm_stars').raty({ starType: 'i' });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		//FOR READ MORE 
		if($('.sc_com').height() >= 80) {
			$('.sc_com').css('height','75px').css('overflow','hidden'),
			$('.read_more').css('display','block')	
		}
		$('.read_more').click(function() {
			$('com_bl').css('height','auto'),
			$('.sc_com').css('height','auto'),
			$('.read_more').css('display','none'),
			$('.read_less').css('display','block')	
		});
		$('.read_less').click(function() {
			$('com_bl').css('height','100px'),
			$('.sc_com').css('height','75px'),
			$('.read_more').css('display','block'),
			$('.read_less').css('display','none')		
		});
	});
</script>