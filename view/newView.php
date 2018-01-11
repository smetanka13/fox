<?php
	require_once 'model/articleModel.php';

	$article = Article::get(!empty($_GET['id']) ? $_GET['id'] : NULL);
?>

<link rel="stylesheet" type="text/css" href="css/new.css">

<div class="main_cont_cnt content_cnt new_cnt animate_cnt">
	<h4 class="main_title tl_mg"><?= $article['title'] ?><br><br><small class="nw_sm"><?= gmdate("Y-m-d", $article['date']) ?></small></h4>
	<div id="nw_imgs">
		<div class="cent_img new_img_cnt">
			<?php
				foreach ($article['images'] as $img) {
			?>
			<a rel="lightbox[new_img_cnt]" href="<?= $img ?>" data-lightbox="image-1"><div title="Нажите . чтобы просмотреть изображение" style="background-image: url(<?= $img ?>);"></div></a>
			<?php } ?>
		</div>
	</div>
	<div class="main_text cont_ab col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<p><?= $article['text'] ?></p>
	</div>
</div>
