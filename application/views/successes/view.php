<?php
	//echo html::script('js/success.js');
?>

<h1><?= Kohana::lang('success.title') ?></h1>
<div id="prelude"><?= Kohana::lang('success.prelude') ?></div>

<?php

$successes = $user->successes;
$titles = ORM::factory('title')->find_all();
$nbr_titles = ORM::factory('title')->count_all();
$nb_users = ORM::factory('user')->where('activated', TRUE)->count_all();

if ($user->id != $user_session->id)
	echo "<p>User : <b>".$user->username."</b></p>";

echo '<p>Succès dévérouillés : <b>'.count($successes).'</b> /'.$nbr_titles.' ';
echo "<small> - <span>".html::anchor('', 'Afficher tous les succès', array('id'=>"maximal_view"))."</span>";
echo "<span>".html::anchor('', 'Vue réduite', array('id'=>"minimal_view"))."</span>";
echo '</small></p><br />';


$n = 0;

// Succès dévérouillés
foreach ($successes as $success)
{
	echo '<div class="leftPart3">';
	echo html::image('images/successes/'.$success->title->name.'.jpg', array('class'=>'success_jgrowl'));
	echo '<b>'.Kohana::lang("success.".$success->title->name.'.name').'</b><br />';
	echo '<small>'.Kohana::lang("success.".$success->title->name.'.desc').'<br />';
	echo '<i>Dévérouillé pour '.$success->title->nbr_users.' jockeys (';
	echo ceil(($success->title->nbr_users*100)/$nb_users).'%)</i></small>';
	echo '</div>';
	if ($n%2 == 1) echo '<div class="clearBoth"></div>';
	$n++;
}

// Succès non dévérouillés
foreach ($titles as $title)
{
	$success = ORM::factory('success')
		->where('user_id', $user->id)
		->where('title_id', $title->id)
		->find();
	if ($success->id == 0)
	{
		echo '<div class="leftPart3 locked">';
		echo html::image('images/successes/'.$title->name.'_g.jpg', array('class'=>'success_jgrowl'));
		echo '<font color="#aaaaaa">';
		echo '<b>'.Kohana::lang("success.".$title->name.'.name').'</b><br />';
		echo '<small>'.Kohana::lang("success.".$title->name.'.desc').'<br />';
		echo '<i>Dévérouillé pour '.$title->nbr_users.' jockeys (';
		echo ceil(($title->nbr_users*100)/$nb_users).'%)</i></small>';
		echo '</font></div>';
		if ($n%2 == 1) echo '<div class="clearBoth"></div>';
		$n++;
	}
}

echo '<div class="clearBoth"></div>';
