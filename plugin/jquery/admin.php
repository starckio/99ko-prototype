<?php
if(isset($_POST['src'])){
	utilWriteJsonFile('data/plugin/jquery.json', array('src' => $_POST['src']));
}
$temp = utilReadJsonFile('data/plugin/jquery.json');
$src = $temp['src'];
?>

<?php include('theme/admin/header.php'); ?>

<main class="main" role="main">
	<h1>Plugin jQuery</h1>

	<div class="text cf">
		<form method="post" action="">
			<div class="field">
				<label><?php echo lang('Source'); ?></label>
				<input type="text" name="src" value="<?php echo $src; ?>" />
			</div>
			<input class="btn" type="submit" value="<?php echo lang('Save'); ?>" />
		</form>
	</div>
</main>

<?php include('theme/admin/footer.php'); ?>