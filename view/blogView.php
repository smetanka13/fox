<?php
	require_once 'model/articleModel.php';

	$articles = Article::getAll();
?>

<link rel="stylesheet" type="text/css" href="css/blog.css">

<div class="wrapper container-fluid blog_back">
	<div class="blog_main_block container-fluid">

		<?php foreach ($articles as $article) { ?>

		<div class="some_info_block_back col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="some_info_block">
				<a title="Нажмите , чтобы читать далее" href="new?id=<?= $article['id_article'] ?>">
					<h4><?= $article['title'] ?></h4>
					<div class="some_info_img cent_img"><img src="<?= $article['img'] ?>"></div>
					<div class="some_info_txt main_text">
						<small><?= gmdate("Y-m-d", $article['date']) ?></small>
						<div>
							<p><?= $article['text'] ?></p>
						</div>
					</div>
				</a>
			</div>
		</div>

		<?php } ?>

	</div>
</div>
