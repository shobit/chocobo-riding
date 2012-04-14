<div style="width: 500px; float:left; position:relative;">
	<?php
	$options = Kohana::lang('forum.types');
	if ($user->loaded) 
	{
		$options['space'] = true;
		$options['shared'] = "Partagés";
	}
	if ($user->has_role(array('modo','admin'))) 
	{
		$options['locked'] = "Vérouillés";
		$options['archived'] = "Archivés";
	}
	?>
	<div class="button-category button">
		<?php echo $options[$category]; ?> (<?php echo $nbr_topics; ?>) &#9660;
	</div>
	<div class="menu-category menu">
		<?php
		foreach ($options as $key=>$value)
		{
			if ($key=='space')
				echo '<div class="action-space"></div>';
			else
				echo '<div class="action-category" name="'.$key.'">'.$value.'</div>';
		}
		?>
	</div>
	<?php 
	
	if ($user->loaded)
		echo html::anchor('forum/discussion/add', 'Créer un nouveau sujet', array('class'=>"button")); 
	
	if ($category=='update'):
		$img = html::image('images/theme/rss.png', array('style'=>'margin-bottom: -3px;'));
		echo html::anchor('http://feeds.feedburner.com/ChocoboRiding-MisesAJour', 
			"$img Flux RSS", array('class'=>'button', 'target'=>'_blank'));
	endif;
	
	?>
</div>	

<div class="paginator">
	<?php
	echo html::image('images/forum/pages.png');
	echo $pagination->render();
	?>
</div>