<h1><?= Kohana::lang('circuit.view_results.title').' '.
	html::image("images/classes/".$circuit->classe.".gif") ?></h1>
<div id="prelude"><?= Kohana::lang('location.'.$circuit->location->code.'.prelude') ?></div>

<?= new View("circuits/description", array('circuit' => $circuit)) ?>

<div class="column4">
	<div class="title">Arrivée</div>
	<table class="circuitInside">
		<?php
		$nbr = count($results);
		foreach ($results as $n => $result) 
		{
			if ($result->chocobo_id == $chocobo->id) {$result->seen = true; $result->save();}
			$ch = $result->chocobo;
			$medal = "normal";
			if ($circuit->race == 1) 
			{
				$medals = array("normal", "normal", "normal", "bronze", "argent", "or");
				$medal = $medals[0];
			}
			?>
			<tr>
				<td><?= html::image('images/icons/'.$medal.'.jpg') ?></td>
				<td class="icons">
					<?= html::image('images/chocobos/'.$ch->display_colour('code').'/generic.gif') ?>
				</td>
				<td><?php echo $ch->vignette(); ?></td>
				<td><?php //if ($result->is_enraged > 0) echo html::image('images/icons/rage.png'); ?></td>
				<td><b><?php if ($circuit->race != 2) echo $result->avg_speed.' km/h'; ?></b></td>
			</tr>
			<?php
		}
		?>
	</table>
</div>

<div class="column4">
	<div class="title">Evénements</div>
	<table class="circuitInside">
		<?php 
		foreach ($facts as $fact)
		{
			if ($fact->general or $fact->result->chocobo_id == $chocobo->id) 
			{
				?><tr>
					<td class="icons"><?= $fact->image() ?></td>
					<td><?= $fact->display() ?></td>
				</tr><?php
			}
		}
		?>

	</table>
</div>

<div class="clearBoth"></div>
<?= $wave ?>
