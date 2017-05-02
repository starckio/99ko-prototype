<!DOCTYPE html>
<html lang="fr">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<title>99ko - Administration</title>

	<link rel="stylesheet" href="theme/admin/styles.css" />

	<?php eval(callHook('adminHead')); ?>

</head>
<body>

<header class="header cf" role="banner">
	<a class="logo" href="./">99ko <?php echo $version; ?></a>
	<nav role="navigation">

		<ul class="menu cf">
			<li><a href="../" target="_blank">Voir le site</a></li>
			<li><a href="">Déconnexion</a></li>
		</ul>

	</nav>
</header>

<div class="cf">

<aside role="seealso" class="sidebar">
	<ul class="menu cf">
	    <li><a href="admin.php"><?php echo lang('Home'); ?></a></li>
	    <li><a href="admin.php?p=configuration"><?php echo lang('Configuration'); ?></a></li>
		<li><a href="admin.php?p=menu"><?php echo lang('Menu'); ?></a></li>
	    <li><a href="admin.php?p=article"><?php echo lang('Articles'); ?></a></li>
	    <?php foreach($plugins as $plugin) if($plugin->adminPage()){ ?>
	    <li><a href="admin.php?p=plugin&id=<?php echo $plugin->get('id'); ?>"><?php echo $plugin->get('name'); ?> </a></li>
	    <?php } ?>
	</ul>
</aside>