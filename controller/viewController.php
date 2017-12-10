<?php
	require_once 'config/globals.php';
	require_once 'config/db.php';
	require_once 'model/mainModel.php';
	require_once 'model/categoryModel.php';
	require_once 'model/userModel.php';

	$private_view = [
		'personal'
	];

	if(isset($_COOKIE['id']) && isset($_COOKIE['email']) && isset($_COOKIE['pass'])) {
		User::login($_COOKIE['id'], $_COOKIE['email'], $_COOKIE['pass']);
	}
	# if(Engine::lookSame($private_view, VIEW) && !$User->logged()) header('Location: '.URL);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script type="text/javascript" src="library/bootstrap/js/bbootstrap.min.js"></script>

	<link rel="SHORTCUT ICON" href="images/icons/title_icon.png" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Concert+One|Inconsolata|Roboto" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="library/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="library/bootstrap/css/bootstrap-theme.min.css">
	<link href="css/main.css" rel="stylesheet" type="text/css"/>
	<!-- for i icons -->
	<script src="https://use.fontawesome.com/d5e6561c97.js"></script>

	<?php
		$GLOBALS['_TITLE'] = NAME;
		$_TITLE_MARKER = '<title></title>';
		ob_start();
		echo $_TITLE_MARKER;
		ob_end_clean();
	?>
</head>
<body>
	<div class="page">
		<?php require_once 'view/layout/addons.php'; ?>
		<?php require_once 'view/layout/menu.php'; ?>
		<?php require_once 'view/layout/header.php'; ?>

		<div id="content" class="container-fluid">

			<?php require_once 'view/' . URI . 'View.php'; ?>

		</div>

		<?php require_once 'view/layout/footer.php'; ?>
		<?php require_once 'view/layout/float.php'; ?>
	</div>
</body>
</html>

<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.mask.min.js"></script>
<script src="js/viewportchecker.js"></script>
<script src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="library/bootstrap/js/bootstrap.min.js"></script>
<script src="js/main.js"></script>

<?= preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1'.$GLOBALS['_TITLE'].'$3', $_TITLE_MARKER) ?>
