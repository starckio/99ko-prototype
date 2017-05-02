<?php include('header.php'); ?>

<main class="main cf" role="main">

	<div class="text">
	<?php foreach($newsList as $news){ ?>
		<a href="?article=<?php echo $news->get('id'); ?>"><h2><?php echo $news->get('name'); ?></h2></a>
		<time style="color:gray;margin-bottom:1.5em;display:block;"><?php echo $news->get('date'); ?></time>
		<?php echo $news->get('content'); ?>
	<?php } ?>
	</div>

</main>

<?php include('footer.php'); ?>