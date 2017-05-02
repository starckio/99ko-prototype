<?php include('header.php'); ?>

<main class="main" role="main">
	<h1><?php echo lang('Management menu'); ?></h1>
	<div class="text cf">
		<?php if($edit){ ?>
		<form method="post" action="admin.php?p=menu">
			<input type="hidden" name="id" value="<?php echo $item->get('id'); ?>" />
			<div class="field">
				<label><?php echo lang('Label'); ?></label><br>
				<input type="text" name="name" value="<?php echo $item->get('name'); ?>" />
			</div>
			<div class="field">
				<label><?php echo lang('URL'); ?></label><br>
				<input type="text" name="url" value="<?php echo $item->get('url'); ?>" />
			</div>
			<div class="field">
				<label><?php echo lang('Parent'); ?></label>
				<div>
					<span class="drop-down"></span>
					<select name="idParent">
						<option value="0"><?php echo lang('None'); ?></option>
						<?php foreach($itemsLevel1 as $item2) if($item2->get('idParent') == 0){ ?>
						<option <?php if($item->get('idParent') == $item2->get('id')){ ?>selected<?php } ?> value="<?php echo $item2->get('id'); ?>"><?php echo $item2->get('name'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<input class="btn" type="submit" value="<?php echo lang('Save'); ?>" />
		</form>
		<?php } else{ ?>
		<form name="menu" method="post" action="admin.php?p=menu&edit=0">
			<div class="field">
				<label><?php echo lang('Add item to'); ?></label>
				<div>
					<span class="drop-down"></span>
					<select name="url" onchange="document.forms['menu'].submit();">
						<option value="?news"><?php echo lang('News'); ?></option>
						<?php foreach($articles as $article){ ?>
						<option value="?article=<?php echo $article->get('id'); ?>"><?php echo $article->get('name'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<ul>
				<?php foreach($itemsLevel1 as $item){ ?>
				<?php if($item->get('idParent') == 0){ ?>
				<li>
					<strong><?php echo $item->get('name'); ?></strong>
					<a href="admin.php?p=menu&up=<?php echo $item->get('id'); ?>">up</a>
					<a href="admin.php?p=menu&down=<?php echo $item->get('id'); ?>">down</a>
					<a href="admin.php?p=menu&edit=<?php echo $item->get('id'); ?>"><?php echo lang('Edit'); ?></a>
					<a href="admin.php?p=menu&del=<?php echo $item->get('id'); ?>"><?php echo lang('Delete'); ?></a>
				<?php } ?>
					<ul>
						<?php foreach($itemsLevel2[$item->get('id')] as $item2){ ?>
						<li>
							<strong><?php echo $item2->get('name'); ?></strong>
							<a href="admin.php?p=menu&up=<?php echo $item2->get('id'); ?>">up</a>
							<a href="admin.php?p=menu&down=<?php echo $item2->get('id'); ?>">down</a>
							<a href="admin.php?p=menu&edit=<?php echo $item2->get('id'); ?>"><?php echo lang('Edit'); ?></a>
							<a href="admin.php?p=menu&del=<?php echo $item2->get('id'); ?>"><?php echo lang('Delete'); ?></a>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</form>
		<?php } ?>
	</div>
</main>


<?php include('footer.php'); ?>