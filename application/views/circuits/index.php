<h1><?php echo Kohana::lang('circuit.index.title') . ' ' . html::image("images/classes/$classe.gif"); ?></h1>
<div id="prelude"><?php echo Kohana::lang('circuit.index.prelude'); ?></div>

<div class="circuit">
	<div class="column2">
		<?= html::image('images/pages/circuits.jpg', array('class'=>'location')) ?>
	</div>

	<?php foreach ($circuits as $circuit): ?>
		<div>
			<?php 
			echo html::anchor('circuits/' . $circuit->id, $circuit->location->display_name());
			$nbr_chocobos = count($circuit->chocobos);
			if ($nbr_chocobos > 0) $nbr_chocobos = "<b>$nbr_chocobos</b>";
			?>
			 (<?php echo $nbr_chocobos; ?>)<br /><?php echo html::image('images/icons/hour2.png'); ?>
			<span style="display: inline;" id="<?php echo $circuit->id; ?>">--:--</span>
			
			<script language=JavaScript>
				decompte(
					'<?= $circuit->id  ?>', 
					'<?= ($circuit->start - time()) ?>', 
					'<?= Kohana::lang('circuit.index.finished') ?>',
					false
				);
			</script>
		</div>
	<?php endforeach; ?>
	
</div>

<div style="float:right;">
	<?= html::anchor('circuit', Kohana::lang('circuit.refresh'), array('class'=>"button")) ?>
</div>

<div class="column4">

	<div class="title"><?= Kohana::lang('circuit.index.last_races') ?></div>
	<?php 
	if (count($results) > 0) { ?>
		<table class="circuitInside">
		<?php
		foreach ($results as $result) {
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
						);
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
