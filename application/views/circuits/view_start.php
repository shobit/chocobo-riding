<?php
	//echo html::script('js/circuit_start.js');
?>

<h1><?= Kohana::lang('circuit.view_start.title').' '.
	html::image("images/classes/".$circuit->classe.".gif") ?></h1>
<div id="prelude"><?= Kohana::lang('location.'.$circuit->location->code.'.prelude') ?></div>

<?= new View("circuits/description", array('circuit' => $circuit)) ?>
	
<div style="float:right;">
		<?php 
		if ($register) 
			echo html::anchor('circuit/register/'.$circuit->id, 
				kohana::lang('circuit.register'), array('class'=>"button"));
		if ($unregister) 
			echo html::anchor('circuit/unregister/'.$circuit->id, 
				kohana::lang('circuit.unregister'), array('class'=>"button"));
		echo html::anchor('circuit/view/'.$circuit->id, 
			kohana::lang('circuit.refresh'), array('class'=>"button"));
		echo html::anchor('circuit', 
			kohana::lang('circuit.back'), array('class'=>"button"));
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
							(<?php
							switch($circuit->race) {
								case 0: echo 'Nv'.$chocobo->level; break;
								case 1: echo 'Côte '.$chocobo->display_fame(); break;
								case 2: echo 'Nv'.$chocobo->level; break;
							}
							?>)
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>
</div>

<div class="clearBoth"></div>
<?= $wave ?>
