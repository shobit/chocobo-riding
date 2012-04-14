<h1><?= Kohana::lang('chocobo.edit.title') ?></h1>
<div id="prelude"><?= Kohana::lang('chocobo.edit.prelude') ?></div>

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

<?= form::open('chocobo/edit') ?>

	<p><b><?= Kohana::lang('chocobo.form.name') ?></b> 
	<?= form::input('name', $form['name']) ?> (<?= html::image('images/icons/'.$chocobo->display_gender('code').'.png').' '.$chocobo->display_gender('zone') ?>)<br />
	<small><?= Kohana::lang('chocobo.explanation.name') ?></small></p>

	</p><?= form::submit('submit', 'Valider') ?></p>

<?= form::close() ?>