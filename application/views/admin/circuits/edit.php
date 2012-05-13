<h1><?php echo Kohana::lang('circuit.edit.title') ?></h1>
<div id="prelude"><?php echo Kohana::lang('circuit.edit.prelude') ?></div>

<!-- Forme originale -->
<?php echo form::open_multipart('circuits/' . $circuit->id . '/edit', array('class'=>'frm_edits')); ?>

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
			<td class="label"><?php echo form::label('code', Kohana::lang('circuit.form.code')) ?></td>
			<td class="value">
				<?php echo form::input('code', $form['code']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?php echo form::label('classe', Kohana::lang('circuit.form.classe')) ?></td>
			<td class="value">
				<?php echo form::input('classe', $form['classe']) ?>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?php echo form::label('image', Kohana::lang('circuit.form.image')) ?></td>
			<td class="value">
				<?php echo form::upload('image', 'storage/circuits/temp/'); ?>
			</td>
		</tr>
		
	</table></p>

</div>

<div class="leftPart">

	<center>
	
	<p><?php echo $circuit->image('thumbmail') ?></p>
	
	<p><?php echo form::submit('submit', 'Valider') ?></p> 

	<p><?php echo html::anchor('circuit', 
		html::image('images/buttons/back.gif')) ?></p>
		
	</center>

</div>

<div class="clearBoth"></div>

<?php echo form::close(); ?>
