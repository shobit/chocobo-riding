<h2>Début de la prochaine course dans 
	<span id="<?php echo $race->id ?>">--:--</span> 
	<?php echo html::image('images/icons/hour2.png') ?>
</h2>

<script>
	decompte(
		'<?php echo $race->id  ?>', 
		'<?php echo ($race->start - time()) ?>', 
		'<?php echo Kohana::lang('race.index.finished') ?>',
		false
	);
</script>

<?php
$display_on = ' style="display:inline;"';
$display_off = ' style="display:none;"';

$registered = ($chocobo->race_id === $race->id);

$register = ( ! $registered and $can_register['success']);
$unregister = ($registered and $can_unregister['success']);
?>

<table class="table1">
	<tr class="first">
		<th class="lenmax">Nom</th>
		<th class="len150">PL</th>
		<th class="len100"></th>
	</tr>
	<tr>
		<td><?php echo $race->circuit->name() ?></td>
		<td><?php echo $race->circuit->pl ?></td>
		<td>
			<?php if ($register) { echo html::anchor('races/' . $race->id . '/register', "S'inscrire", array('class' => 'button green')); } ?>
			<?php if ($unregister) { echo html::anchor('races/' . $race->id . '/unregister', 'Se désinscrire', array('class' => 'button red')); } ?>
		</td>
	</tr>
</table>

<?php if (count($race->chocobos) > 0): ?>
	<h2>Chocobos inscrits : <?php echo count($race->chocobos) ?> /6</h2>

	<table class="table1">
		<tr class="first">
			<th class="lenmax">Nom</th>
		</tr>

		<?php foreach ($race->chocobos as $chocobo): ?>
			<tr>
				<td><?php echo $chocobo->vignette() ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>

<?php //echo $wave ?>
