<style>
.hour {font-size: 10px; color: #999;}
</style>

<h1>Evénements</h1>
<div id="prelude">Voici les 15 derniers commits effectués sur le site. Vous pouvez trouver plus d'informations sur le code (qui est open source) en allant sur la page Github. Restez au fait des nouveautés du site ! Si vous avez des questions ou autres, n'hésitez pas à les poser sur le forum ou la Shoutbox :)</div>

<?php

$feed = "https://github.com/Menencia/chocobo-riding/commits/master.atom";
$posts = feed::parse($feed);
$last = '';
foreach ($posts as $i => $post)
{
	$date = explode('T', $post['updated']);
	$date1 = explode('-', $date[0]);
	$tmp = explode('-', $date[1]);
	$date2 = explode(':', $tmp[0]);
	$time = mktime($date2[0], $date2[1], $date2[2], $date1[1], $date1[2], $date1[0]);
	$time += 7*3600;
	$new = $date[0];
	if ($i != 0 and $new !== $last) echo '<br /><br />';
	if ($new !== $last) echo '<b>' . date::display($time) . '</b><br />';
	echo '<br />' . $post['title'] . ' <span class="hour">' . date('H:i', $time) . '</span>';
	$last = $new;
}

?>