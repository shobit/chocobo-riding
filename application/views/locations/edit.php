<h1><?= Kohana::lang('location.edit.title') ?></h1>
<div id="prelude"><?= Kohana::lang('location.edit.prelude') ?></div>

<!-- Forme originale -->
<?php echo form::open_multipart('location/edit/'.$location->id, array('class'=>'frm_edits')); ?>

<?php
$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error.'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}
?>

<div class="leftPart2">
	
	<p><table width="100%">
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('code', Kohana::lang('location.form.code')) ?></td>
			<td class="value">
				<?= form::input('code', $form['code']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('speed', Kohana::lang('location.form.speed')) ?></td>
			<td class="value">
				<?= form::input('speed', $form['speed']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('intel', Kohana::lang('location.form.intel')) ?></td>
			<td class="value">
				<?= form::input('intel', $form['intel']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('endur', Kohana::lang('location.form.endur')) ?></td>
			<td class="value">
				<?= form::input('endur', $form['endur']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('classe', Kohana::lang('location.form.classe')) ?></td>
			<td class="value">
				<?= form::input('classe', $form['classe']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= form::label('image', Kohana::lang('location.form.image')) ?></td>
			<td class="value">
				<?= form::upload('image', 'storage/locations/temp/'); ?>
			</td>
		</tr>
		
	</table></p>

</div>

<div class="leftPart">

	<center>
	
	<p><?= $location->display_image('thumbmail') ?></p>
	
	<p><?= form::submit('submit', 'Valider') ?></p> 

	<p><?= html::anchor('location', 
		html::image('images/buttons/back.gif')) ?></p>
		
	</center>

</div>

<div class="clearBoth"></div>

<?= form::close(); ?>
