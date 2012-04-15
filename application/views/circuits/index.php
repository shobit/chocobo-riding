<?php 
	//echo html::script('js/circuit_time_left.js'); 
?>

<h1><?= Kohana::lang('circuit.index.title').' '.
	html::image("images/classes/".$chocobo->classe.".gif") ?></h1>
<div id="prelude"><?= Kohana::lang('circuit.index.prelude') ?></div>

<div class="circuit">
	<div class="column2">
		<?= html::image('images/pages/circuits.jpg', array('class'=>'location')) ?>
	</div>

	<?php
	$types_plur_name = array(
		Kohana::lang('circuit.index.trainings'), 
		Kohana::lang('circuit.index.competitions'), 
		Kohana::lang('circuit.index.rides'));
	$types_sing_name = array(
		Kohana::lang('circuit.index.training'), 
		Kohana::lang('circuit.index.competition'), 
		Kohana::lang('circuit.index.ride'));
	for ($i=0; $i<=2; $i++) {
	?>
		<div class="column2">
			<div class="title"><?= $types_plur_name[$i] ?></div>
			<table class="circuitInside">
			<?php foreach (${'circuits_'.$i} as $circuit) { ?>
				<tr>
					<td class="icon">
						<?= html::image('images/icons/cup'.$circuit->status.'.png') ?>
					</td>
					<td>
						<?php 
						echo html::anchor('circuits/'.$circuit->id, $circuit->location->display_name());
						$nbr_chocobos = count($circuit->chocobos);
						if ($nbr_chocobos > 0) $nbr_chocobos = "<b>$nbr_chocobos</b>";
						?>
						 (<?= $nbr_chocobos ?>)<br /><?= html::image('images/icons/hour2.png') ?>
						<span style="display: inline;" id="<?= $circuit->id ?>">--:--</span>
						<script language=JavaScript>
							decompte(
								'<?= $circuit->id  ?>', 
								'<?= ($circuit->start - time()) ?>', 
								'<?= Kohana::lang('circuit.index.finished') ?>',
								false
							);
						</script>
					</td>
				</tr>

			<?php } ?>
		</table>
		</div>
	<?php } ?>
	
	<div class="clearBoth"></div>
	
</div>

<div style="float:right;">
	<?= html::anchor('circuit', Kohana::lang('circuit.refresh'), array('class'=>"button")) ?>
</div>

<div class="column4">

	<div class="title"><?= Kohana::lang('circuit.index.last_races') ?></div>
	<?php 
	//$last_results = $chocobo->results;
	if (count($last_results) > 0) { ?>
		<table class="circuitInside">
		<?php
		foreach ($last_results as $result) {
			//$result->circuit->revise();
			if ($result->circuit->id > 0) 
			{
				?>
				<tr>
					<td class="icon">
						<?= html::image('images/icons/cup3.png') ?>
					</td>
					<td>
						<?php
						echo html::anchor(
							'circuit/view/'.$result->circuit->id, 
							$result->circuit->location->display_name()
						).', ';
						echo $types_sing_name[$result->circuit->race];
						?>
					</td>
					<td>
						<b><?php $tl = gen::time_left($result->circuit->start); echo $tl['short']; ?></b> 
						<?php if (!$result->seen) echo "<i>(non vu)</i>";?>
					</td>
				</tr>
				<?php 
			}
		} ?>
		</table>
	<?php } else { ?>
		<p><i>Aucun</i></p>
	<?php } ?>

</div>
