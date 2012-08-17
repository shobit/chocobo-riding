<div class="nav">
	<?php echo HTML::anchor('#/github', 'Github') ?>
	<?php echo HTML::anchor('#/ohloh', 'Ohloh') ?>
	<?php echo HTML::anchor('#/changelog', 'Changelog') ?>
</div>

<div class="section" id="github">

	Chocobo Riding est un projet open-source hébergé sur Github.
	
	<p>Développeurs :</p>

	<p>
		<iframe src="http://githubbadge.appspot.com/badge/Menencia" style="border: 0;height: 142px;width: 200px;overflow: hidden;" frameBorder=0></iframe>
	</p>
</div>

<div class="section" id="ohloh">
	
	<script type="text/javascript" src="http://www.ohloh.net/p/585871/widgets/project_basic_stats.js"></script>

</div>

<div class="section" id="changelog">

	<?php 
	if (Auth::instance()->logged_in('admin'))
	{
		echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'button blue fright fancybox fancybox.ajax'));
		echo '<div class="clearright" style="height: 10px;"></div>';
	}
	
	foreach ($updates as $update)
	{
		echo '<div class="update">';
		if (Auth::instance()->logged_in('admin'))
		{
			echo '<div class="options">';
			echo html::anchor('update/edit/' . $update->id, html::image('images/icons/edit.png'), array('class' => 'fancybox fancybox.ajax'));
			echo '</div>';
		}
		echo ' <div class="wrapper-type"><span class="type ' . $update->type . '">' . $update->type . '</span></div>';
		echo ' <div class="title">' . $update->title . '</div>';
		echo ' <div class="content">' . $update->content . '</div>';
		echo ' <div class="date">' . Date::display(strtotime($update->date)) . '</div>';
		echo ' <div class="clearleft"></div>';
		echo '</div>';
	}
	?>

</div>

<script>
$(function(){

	init_nav('#/github');

	$('.update').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});	

});
</script>