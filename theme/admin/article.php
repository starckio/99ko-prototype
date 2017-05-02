<?php include('header.php'); ?>

<main class="main" role="main">
	<h1><?php echo lang('Management articles'); ?></h1>
	<div class="text cf">
		<?php if($edit){ ?>
		<form method="post" action="admin.php?p=article">
			<input type="hidden" name="id" value="<?php echo $article->get('id'); ?>" />
		    <input type="hidden" name="type" value="<?php echo $article->get('type'); ?>" />
			<div class="field">
				<label><?php echo lang('Title'); ?></label>
				<input type="text" name="name" value="<?php echo $article->get('name'); ?>" />
			</div>
			<div class="field">
				<label><?php echo lang('Content'); ?></label>
				<textarea name="content"><?php echo $article->get('content'); ?></textarea>
			</div>
			<div class="field">
				<label><?php echo lang('Homepage'); ?> ?</label>
				<div>
					<span class="drop-down"></span>
					<select name="homepage">
						<option value="0"><?php echo lang('No'); ?></option>
						<option <?php if($article->get('homepage')){ ?>selected<?php } ?> value="1"><?php echo lang('Yes'); ?></option>
					</select>
				</div>
			</div>
			<input class="btn" type="submit" value="<?php echo lang('Save'); ?>" />
		</form>
		<?php } else{ ?>
		<form name="article" method="post" action="admin.php?p=article&edit=0">
			<div class="field">
				<label><?php echo lang('Add article'); ?> ou une page</label>
				<div>
					<span class="drop-down"></span>
					<select name="type" onchange="document.forms['article'].submit();">
			            <option value="page"><?php echo lang('Page'); ?></option>
						<option value="news"><?php echo lang('News'); ?></option>
					</select>
				</div>
			</div>
			<ul>
		    	<li>
			        <ul>
						<?php foreach($pages as $page){ ?>
						<li>
							<strong><?php echo $page->get('name'); ?></strong><br />
							<a href="admin.php?p=article&edit=<?php echo $page->get('id'); ?>"><?php echo lang('Edit'); ?></a> - <a href="admin.php?p=article&del=<?php echo $page->get('id'); ?>"><?php echo lang('Delete'); ?></a>
							 <br /><br />
						</li>
						<?php } ?>
		            </ul>
		        </li>
		        <li><hr /></li>
		        <li>
			        <ul>
						<?php foreach($news as $article){ ?>
						<li>
							<strong><?php echo $article->get('name'); ?></strong><br />
							<a href="admin.php?p=article&edit=<?php echo $article->get('id'); ?>"><?php echo lang('Edit'); ?></a> - <a href="admin.php?p=article&del=<?php echo $article->get('id'); ?>"><?php echo lang('Delete'); ?></a>
							 <br /><br />
						</li>
						<?php } ?>
			        </ul>
			    </li>
			</ul>
		</form>
		<?php } ?>
	</div>
</main>

<?php include('footer.php'); ?>