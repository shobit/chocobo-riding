<?php
echo form::open('fusion');

$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error.'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}

$boxes = $user->get_boxes();
echo form::hidden('chocobo', $chocobo->id);
echo form::hidden('boxes', $boxes);
$genders = array('', 'female', 'male');

?><h2>Partenaires <?= html::image('images/icons/'.$genders[$gender].'.png'); ?></h2><?php

if (count($partners) >0) {
	foreach ($partners as $partner) {
		echo '<p>'.form::radio("partner", $partner->id);
		echo $partner->image('mini').' '.$partner->name.', ';
		echo 'nv'.$partner->level.'<small>/'.$partner->lvl_limit.'</small> ';
		echo '('.$partner->display_status();
		if ($partner->mated >time()) echo ' - '.ceil(($partner->mated - time())/3600).'h'; 
		echo ')</p>';
	}
} 
else {
	?>
	<input type="hidden" name="partner" value="0" />

	<i>Aucun partenaires.</i>
	<?php
}
?>

<h2>Choix de la Noix</h2>

<?php
// Nuts
if (count($nuts) > 0) 
{
	foreach ($nuts as $nut) 
	{
		echo form::radio("nut", $nut->id);
		echo $nut->vignette();
	}
} 
else 
{
?>
	<input type="hidden" name="nut" value="0" />
	<i>Aucune noix.</i>
<?php
}

echo '<p><input type="submit" value="Fusion!" onClick="if (verifyFusion()) {return true;} else {return false;}" /></p>';

echo form::close();

?>

