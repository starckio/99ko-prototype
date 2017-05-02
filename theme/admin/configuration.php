<?php include('header.php'); ?>

<main class="main" role="main">

	<h1><?php echo lang('General configuration'); ?></h1>

<div class="text cf">
	<form method="post" action="">
	    <div class="field">
	        <label><?php echo lang('Website name'); ?></label>
	        <input type="text" name="name" value="<?php echo $name; ?>" />
	    </div>
	    <div class="field">
	        <label><?php echo lang('Website URL'); ?></label>
	        <input type="text" name="url" value="<?php echo $url; ?>" />
	    </div>
	    <div class="field">
	        <label><?php echo lang('Website lang'); ?></label>
	        <div>
	        	<span class="drop-down"></span>
		        <select name="lang">
		            <?php foreach($langs as $k=>$v){ ?>
		            <option <?php if($lang == $k){ ?>selected<?php } ?> value="<?php echo $k; ?>"><?php echo $k; ?></option>
		            <?php } ?>
		        </select>
		    </div>
	    </div>
	    <div class="field">
	        <label><?php echo lang('Theme'); ?></label>
	        <div>
	        	<span class="drop-down"></span>
			    <select name="theme">
		            <option value="default">default</option>
		        </select>
		    </div>
	    </div>
	    <input class="btn" type="submit" value="<?php echo lang('Save'); ?>" />
	</form>
</div>

</main>

<?php include('footer.php'); ?>