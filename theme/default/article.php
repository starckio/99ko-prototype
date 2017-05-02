<?php include('header.php'); ?>

<main class="main cf" role="main">

	<div class="text">
		<h1><?php echo $article->get('name'); ?></h1>
		
		<?php if($article->get('date') != ''){ ?>
		<time style="color:gray;margin-bottom:1.5em;display:block;"><?php echo $article->get('date'); ?></time>
		<?php } ?>
		
		<?php echo $article->get('content'); ?>
	</div>

</main>

<?php include('footer.php'); ?>