<h1><?php echo Kohana::lang('tutorial.title'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('tutorial.prelude'); ?></div>

<div class="leftPart2">

	<p><b><?= Kohana::lang('tutorial.help.title'); ?></b></p>
	<?php
		for ($i=1; $i<=3; $i++) 
		{
			echo '<div class="help">'.Kohana::lang('tutorial.help.content'.$i)."</div>";
		}	
	?>
	<p><?= Kohana::lang('tutorial.help.final') ?></p>

</div>

<div class="leftPart">

	<center>
		
		<?= html::image('images/theme/mog.jpg') ?><br /><br />
	
		<?= html::anchor(
			'https://docs.google.com/document/d/1q6nPXJDjWkikMpvIuOGqGLJBfwkLVbOivMW0iYtPvts/edit', 
			Kohana::lang('tutorial.link')) ?>
	
	</center>

</div>

<div class="clearBoth"></div>
