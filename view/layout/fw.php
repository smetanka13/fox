<link rel="stylesheet" href="css/fw.css">
<div class="fw-addons">
	<div id="ajax_error"></div>

	<div class="message">
		<div class="bckgnd" onclick="FW.showMessage()"></div>
		<div class="cnt"><div class="content"></div></div>
	</div>

	<div id="ajax_load" class=""></div>

	<script>
		window.onload = function() {<?= (isset($_GET['msg']) ? "FW.showMessage('{$_GET['msg']}')" : '') ?>};
	</script>
</div>
