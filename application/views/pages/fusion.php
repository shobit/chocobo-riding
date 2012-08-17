<?php
echo Form::open('fusion');

$boxes = $user->get_boxes();
echo Form::hidden('chocobo', $chocobo->id);
echo Form::hidden('boxes', $boxes);
$genders = array('', 'female', 'male');
?>

<h2>Partenaires <?php echo html::image('images/icons/'.$genders[$gender].'.png'); ?></h2>

<?php
if (count($partners) > 0) 
{
	foreach ($partners as $partner) 
	{
		echo '<p>'.Form::radio('partner', $partner->id);
		echo $partner->name.', ';
		echo 'nv'.$partner->level.'<small>/'.$partner->lvl_limit.'</small> ';
		echo '('.$partner->display_status();
		if ($partner->mated >time()) echo ' - '.ceil(($partner->mated - time())/3600).'h'; 
		echo ')</p>';
	}
} 
else 
{
	?>
	<input type="hidden" name="partner" value="0" />

	<p><i>Aucun partenaires.</i></p>
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
		echo '<p>';
		echo Form::radio("nut", $nut->id);
		echo $nut->vignette();
		echo '</p>';
	}
} 
else 
{
	?>
	<input type="hidden" name="nut" value="0" />
	<p><i>Aucune noix.</i></p>
	<?php
}

echo '<p><input type="submit" value="Fusion!" onClick="if (verifyFusion()) {return true;} else {return false;}" /></p>';

echo Form::close();

$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error[0].'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}

?>
