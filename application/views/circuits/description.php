<?php 
	//echo html::script('js/circuit_time_left.js');
?>

<div class="circuit">
	<div class="column2">
		<?= $circuit->location->display_image('thumbmail') ?>
	</div>
		
	<div class="column2">
		<div class="title"><?= $circuit->display_race('zone') ?></div>
		<table class="circuitInside">
			<tr>
				<td class="icon">
					<?= html::image("images/icons/cup".$circuit->status.".png") ?>
				</td>
				<td>
					<?= $circuit->location->display_name() ?>
				</td>
			</tr>
			<tr>
				<td class="icon">
					<?= html::image("images/icons/distance.png") ?>
				</td>
				<td>
					<?= $circuit->length.' kms' ?>
				</td>
			</tr>
			<tr>
				<td class="icon">
					<?= html::image("images/icons/hour.png") ?>
				</td>
				<td>
					<b><span style="display: inline;" id="<?= $circuit->id ?>">--:--</span></b>
				</td>
			</tr>
		</table>
		<script language=JavaScript>
			$(document).ready(function() {
				decompte(
					'<?= $circuit->id ?>', 
					'<?= ($circuit->start - time()) ?>', 
					'<?= Kohana::lang('circuit.index.finished') ?>',
					true
				);
			});
		</script>
	</div>
			
	<div class="column2">
		<div class="title"><?= Kohana::lang('circuit.description.strategy') ?></div>
		<table class="circuitInside">
			<tr>
				<td class="icon">
					<?= html::image('images/chocobos/'.
						gen::colour($circuit->surface).'/generic.gif') ?>
				</td>
				<td>
					<?= gen::colour($circuit->surface, 'display') ?>
				</td>
			</tr>
			<?php
			if ($circuit->location->speed > 0) {
				?>
				<tr>
					<td class="icons"><?= html::image('images/icons/speed.png') ?></td>
					<td>Vitesse <small><b>x<?= $circuit->location->speed ?></b></small></td>
				</tr>
				<?php
			}
			if ($circuit->location->intel > 0) {
				?>
				<tr>
					<td class="icons"><?= html::image('images/icons/intel.png') ?></td>
					<td>Intelligence <small><b>x<?= $circuit->location->intel ?></b></small></td>
				</tr>
				<?php 
			}
			if ($circuit->location->endur > 0) {
				?>
				<tr>
					<td class="icons"><?= html::image('images/icons/endur.png') ?></td>
					<td>Endurance <small><b>x<?= $circuit->location->endur ?></b></small></td>
				</tr>
				<?php
			} ?>
		</table>
	</div>
		
	<div class="column2">
		<div class="title"><?= Kohana::lang('circuit.description.gains') ?></div>
		<table class="circuitInside">
			<?php
			switch ($circuit->race) {
				case 0:
					?>
					<tr>
						<td class="icons"><?= html::image('images/icons/exp.png') ?></td>
						<td><?= Kohana::lang('circuit.description.exp') ?></td>
					</tr>
					<tr>
						<td class="icons"><?= html::image('images/icons/skills.png') ?></td>
						<td><?= Kohana::lang('circuit.description.apt') ?></td>
					</tr>
					<?php 
					break;
				case 1:
					?>
					<tr>
						<td class="icons"><?= html::image('images/icons/cel.png') ?></td>
						<td>Côte</td>
					</tr>
					<tr>
						<td class="icons"><?= html::image('images/icons/gils.png') ?></td>
						<td>Argent</td>
					</tr>
					<tr>
						<td class="icons"><?= html::image('images/icons/items.png') ?></td>
						<td>Objets</td>
					</tr>
					<?php
					break;
				case 2:
					?>
					<tr>
						<td class="icons"><?= html::image('images/icons/breath.png') ?></td>
						<td>Souffle</td>
					</tr>
					<tr>
						<td class="icons"><?= html::image('images/icons/energy.png') ?></td>
						<td>Energie</td>
					</tr>
					<tr>
						<td class="icons"><?= html::image('images/icons/moral.png') ?></td>
						<td>Moral</td>
					</tr>
					<?php
					break;
			}
			?>
		</table>
	</div>

	<div class="clearBoth"></div>
	
</div>
