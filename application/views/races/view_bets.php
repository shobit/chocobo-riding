<h1><?php echo Kohana::lang('race.view_bets.title').' '.
	html::image("images/classes/".$circuit->classe.".gif") ?></h1>
<div id="prelude"><?php echo Kohana::lang('location.'.$circuit->location->code.'.prelude') ?></div>

<?php echo new View("circuits/description", array('circuit' => $circuit)) ?>

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
						<td><?php echo html::image('images/icons/normal_sepia.jpg') ?></td>
						<td class="icons"></td>
						<td>
							<?php echo $chocobo->vignette(); ?>
							(<?php echo 'Côte '.$chocobo->display_fame() ?>)
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>
</div>

<div class="clearBoth"></div>
<?php echo $wave ?>