<style>
.update {position: relative; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 20px;}
.update .wrapper-type {float: left; width: 90px; text-align: right; padding-right: 10px;}
	.type {color: #000; padding: 3px; font-family: monospace;}
	.add {background-color: #090; color: #fff;}
.update .title {margin-left: 100px; font-weight: bold; line-height: 18px;}
.update div.content {margin-left: 100px; line-height: 18px;}
.update .date {margin-left: 100px; font-size: 10px; color: #999; line-height: 18px;}
</style>

<h1>Evénements</h1>
<div id="prelude">Voici les dernières mises à jour du site. 
	Restez au fait des nouveautés du site ! 
	Si vous avez des questions ou autres, n'hésitez pas à les poser sur le forum ou la Shoutbox :)</div>

<?php

if ($user->has_role('admin'))
{
	echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'button fancybox fancybox.ajax'));
}

foreach ($updates as $update)
{
	echo '<div class="update">';
	if ($user->has_role('admin'))
	{
		echo '<div class="options">';
		echo html::anchor('update/edit/' . $update->id, html::image('images/icons/edit.png'), array('class' => 'fancybox fancybox.ajax'));
		echo '</div>';
	}
	echo ' <div class="wrapper-type"><span class="type ' . $update->type . '">' . $update->type . '</span></div>';
	echo ' <div class="title">' . $update->title . '</div>';
	echo ' <div class="content">' . $update->content . '</div>';
	echo ' <div class="date">' . date::display(strtotime($update->date)) . '</div>';
	echo ' <div class="clearleft"></div>';
	echo '</div>';
}

?>

<script>
$(function(){
	
	$('.update').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});	

})
</script>
