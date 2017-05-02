<!DOCTYPE html>
<html lang="fr">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<title><?php echo $metaTitle; ?></title>
	<base href="<?php echo $url; ?>" />
	<meta name="description" content="<?php echo $metaDescription; ?>">
	<meta name="keywords" content="99ko,etc…">

	<link rel="stylesheet" href="theme/default/styles.css" />
	<script src="theme/default/scripts.js" type="text/javascript"></script>
	<?php eval(callHook('themeHead')); ?>

</head>
<body>

<header class="header cf" role="banner">
	<a class="logo" href="./">
		<img src="<?php echo $url; ?>theme/default/logo.svg" alt="<?php echo $metaTitle; ?>" />
	</a>
	<div class="toggle">Menu</div>
	<nav class="navigation" role="navigation">
	
		<ul class="menu cf">
		<?php foreach($itemsLevel1 as $item){ ?>
			<?php if($item->get('idParent') == 0){ ?>
			<li><a href="<?php echo $item->get('url'); ?>"><?php echo $item->get('name'); ?></a></li>
			<?php } ?>
		    	<!--<ul>
			    	<?php foreach($itemsLevel2[$item->get('id')] as $item2){ ?>
			    	<li><a href="<?php echo $item2->get('url'); ?>"><?php echo $item2->get('name'); ?></a></li>
			    	<?php } ?>
		    	</ul>-->
		<?php } ?>
		</ul>
	
	</nav>
</header>