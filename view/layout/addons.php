<link rel="stylesheet" href="css/addons.css">

<div id="ajax_error" class="fw-theme-grad-anim"></div>
<div id="message_whole" onclick="showMessage()"></div>
<div id="message_cnt"><div id="message_block"></div></div>
<i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="ajax_load"></i>

<script>

	function showMessage(str) {
		var main = $("#message_whole");
		var content = $("#message_block");
		var container = $("#message_cnt");

		if(main.css("display") == "none") {
			content.html(str);
			container.show();
			main.fadeIn(100, function() {
				container.css("top", "100px");
			});
		} else {
			container.css("top", "-"+container.height()+"px");
			main.fadeOut(200, function() {
				container.hide();
			});
		}
	}

	function ajaxError(str) {

		var block = $('#ajax_error');

		block.html(str);
		block.fadeIn(100, function() {
			setTimeout(function () {
				block.fadeOut(100);
			}, 2000);
		});

	}

	function ajaxLoad(mode) {
		var block = $("#ajax_load");
		if(mode) {
			block.show();
			block.css("top", "calc(100% - 128px)");
		} else {
			block.hide();
			block.css("top", "100%");
		}
	}

	function ajaxController(params, files = null) {

		ajaxLoad(true);
		var data = new FormData();

		// Добавляет в data все ключи и их значения
		for(var key in params) {
			if(key == 'callback' || key == 'local_params') continue;
			data.append(key, params[key]);
		}

		// Если присутствуют файлы, добавить их в data
		if(files != null) {
			for(var key in files) {
				$.each(files[key][0].files, function(i, file) {
					data.append(key+i, file);
				});
			}
		}

		// Отправка data в PHP контроллер аjax запросов
		$.ajax({
			url: "remote",
			type: "POST",
			data: data,
			contentType: false,
			processData: false,
			error: function() {
				ajaxLoad(false);
				ajaxError('Server error.');
			},
			success: function(json) {
				console.log(json);
				ajaxLoad(false);
				var data = JSON.parse(json);

				if(typeof(params.local_params) != 'undefined') {
					var local_params = params.local_params;
				} else {
					var local_params = null;
				}

				if(typeof(data.permission) != 'undefined') {
					ajaxError(data.permission);
				} else {
					params.callback(data, local_params);
				}
			}
		});
	}

	$(document).ready(function() {

		<?php
			if(isset($_GET['msg'])) {
				echo "showMessage('".$_GET['msg']."');";
			}
		?>

	});

</script>
