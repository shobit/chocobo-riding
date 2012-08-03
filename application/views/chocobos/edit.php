<h1><?php echo Kohana::lang('chocobo.edit.title') ?></h1>
<div id="prelude"><?php echo Kohana::lang('chocobo.edit.prelude') ?></div>

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

<?php echo form::open('chocobo/edit') ?>

	<p><b><?php echo Kohana::lang('chocobo.form.name') ?></b> 
	<?php echo form::input('name', $form['name']) ?> (<?php echo html::image('images/icons/'.$chocobo->display_gender('code').'.png').' '.$chocobo->display_gender('zone') ?>)<br />
	<small><?php echo Kohana::lang('chocobo.explanation.name') ?></small></p>

	</p><?php echo form::submit('submit', 'Valider') ?></p>

<?php echo form::close() ?>