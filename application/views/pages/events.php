<style>
.hour {font-size: 10px; color: #999;}
</style>

<h1>Evénements</h1>
<div id="prelude">Voici les dernières mises à jour du site. 
	Restez au fait des nouveautés du site ! 
	Si vous avez des questions ou autres, n'hésitez pas à les poser sur le forum ou la Shoutbox :)</div>

<?php

if ($user->has_role('admin'))
{
	echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'fancybox fancybox.ajax'));
}

foreach($updates as $update)
{
	echo '<div class="update">';
	echo ' <div class="type">' . $update->type . '</div>';
	echo ' <div class="title">' . $update->title . '</div>';
	echo ' <div class="content">' . $update->content . '</div>';
	echo ' <div class="date">' . date::display($update->date) . '</div>';
	echo '</div>';
}

?>