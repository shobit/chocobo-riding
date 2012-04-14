<h1><?= Kohana::lang('circuit.view_bets.title').' '.
	html::image("images/classes/".$circuit->classe.".gif") ?></h1>
<div id="prelude"><?= Kohana::lang('location.'.$circuit->location->code.'.prelude') ?></div>

<?= new View("circuits/description", array('circuit' => $circuit)) ?>

<div style="float:right;">
		<?php 
		echo html::anchor('circuit/view/'.$circuit->id, 
			html::image("images/buttons/refresh.gif"));
		echo html::anchor('circuit', html::image("images/buttons/back.gif"));
		?>
</div>
	
<div class="column3">
	<?php
	$nbr_chocobos = count($circuit->chocobos);
		if ($nbr_chocobos >0) {
		?>
			<div class="title">Départ</div>
			<table class="circuitInside">
				<?php foreach ($circuit->chocobos as $chocobo) { ?>
					<tr>
						<td><?= html::image('images/icons/normal_sepia.jpg') ?></td>
						<td class="icons"><?= $chocobo->display_image('mini'); ?></td>
						<td>
							<?php echo $chocobo->vignette(); ?>
							(<?= 'Côte '.$chocobo->display_fame() ?>)
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>
</div>

<div class="clearBoth"></div>
<?= $wave ?>