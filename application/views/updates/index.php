<style>

</style>

<h2>Améliorations</h2>

<?php 
if ($user->has_role('admin'))
{
	echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'button blue fright fancybox fancybox.ajax'));
	echo '<div class="clearright"></div>';
}
?>

<div class="clearright"></div>

<?php
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
