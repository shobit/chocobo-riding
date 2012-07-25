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
	echo html::anchor('', 'Ajouter');
}

var_dump(count($updates));

?>