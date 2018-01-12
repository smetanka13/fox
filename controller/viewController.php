<?php
	require_once 'model/viewModel.php';
	require_once 'model/categoryModel.php';

	$private_view = [
		'personal'
	];

	if(isset($_COOKIE['id']) && isset($_COOKIE['email']) && isset($_COOKIE['pass'])) {
		User::login($_COOKIE['id'], $_COOKIE['email'], $_COOKIE['pass']);
	}
	# if(Engine::lookSame($private_view, VIEW) && !$User->logged()) header('Location: '.URL);

	if(method_exists('View', URI)) $_DATA = call_user_func('View::' . URI);
?>

<!DOCTYPE html>

<?php require_once 'view/layout/head.php' ?>

<div class="page">

	<?php require_once 'view/layout/fw.php' ?>
	<?php require_once 'view/layout/menu.php' ?>
	<?php require_once 'view/layout/header.php' ?>

	<div id="content" class="container-fluid">

		<?php require_once 'view/' . URI . 'View.php' ?>

	</div>

	<?php require_once 'view/layout/footer.php' ?>
	<?php require_once 'view/layout/float.php' ?>

</div>

<?php require_once 'view/layout/noncrit.php' ?>

<script>
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip();
	});
	$.fn.extend ({
	    animateCss: function (animationName) {
	        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
	        this.addClass('animated ' + animationName).one(animationEnd, function() {
	            $(this).removeClass('animated ' + animationName);
	        });
	    }
	});
	// FW.ajax.betasend({
	// 	model: 'category',
	// 	method: 'getCategories',
	// 	callback: function() {}
	// });
</script>
