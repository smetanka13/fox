<link rel="stylesheet" href="css/fw.css">

<div id="ajax_error" class="fw-theme-grad-anim"></div>
<div id="message_whole" onclick="FW.showMessage()"></div>
<div id="message_cnt"><div id="message_block"></div></div>
<i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="ajax_load"></i>

<script>

	$(document).ready(function() {

		<?php
			if(isset($_GET['msg'])) {
				echo "showMessage('".$_GET['msg']."');";
			}
		?>

	});

</script>
