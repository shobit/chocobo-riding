<style>
.update {
	position: relative;
	margin-top: 10px;
	border-top: 1px solid #DDD;
	padding-top: 5px;
	padding-bottom: 10px;
}
.update .wrapper-type {float: left; width: 100px; text-align: right;}
	.type {
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		text-transform: uppercase;
		background-color: #333;
		color: #fff;
		font-size: 10px;
		font-weight: bold;
		padding: 3px 6px;
		margin-right: 10px;
		text-shadow: 0 1px 1px rgba(50,50,50,0.5)
	}
	.added {background-color: #7C29A9; color: #fff;}
	.fixed {background-color: #226EC7; color: #fff;}
	.improved {background-color: #97B931; color: #fff;}
	.new {background-color: #7c29a9; color: #fff;}
.update .title {margin-left: 100px; font-weight: bold; line-height: 18px;}
.update div.content {margin-left: 100px; line-height: 18px;}
.update .date {margin-left: 100px; font-size: 10px; color: #999; line-height: 18px;}
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
