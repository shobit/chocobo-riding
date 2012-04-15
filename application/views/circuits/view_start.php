<style>
	.loading {font-size: 10px; color: #999; font-style: italic; display: none;}
	
</style>

<h1><?php echo Kohana::lang('circuit.view_start.title') . ' ' . html::image('images/classes/' . $circuit->classe . '.gif'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('location.' . $circuit->location->code . '.prelude'); ?></div>

<?php echo new View("circuits/description", array('circuit' => $circuit)); ?>
	
<div style="float:right;">
	<?php 
	$display_on = ' style="display:inline;"';
	$display_off = ' style="display:none;"';
	
	$registered = ($chocobo->circuit_id === $circuit->id);
	
	$register = ( ! $registered and $can_register['success']) ? $display_on: $display_off;
	$unregister = ($registered and $can_unregister['success']) ? $display_on: $display_off;
	
	if ( ! $registered) 
	{
		echo $can_register['msg'];
	}
	
	if ($registered) 
	{
		echo $can_unregister['msg'];
	}
	
	?>
	
	<span id="register"<?php echo $register; ?>>
		+ <?php echo html::anchor('circuits/' . $circuit->id . '/register', kohana::lang('circuit.register'), array('id' => 'a-subscribe')); ?> 
		<span class="loading">en cours</span>
	</span>
	
	<span id="unregister"<?php echo $unregister; ?>>
		- <?php echo html::anchor('circuits/' . $circuit->id . '/unregister', kohana::lang('circuit.unregister'), array('id' => 'a-unsubscribe')); ?> 
		<span class="loading">en cours</span>
	</span>
	
	<?php
	echo html::anchor('circuits/' . $circuit->id, kohana::lang('circuit.refresh'), array('class' => 'button'));
	
	echo html::anchor('circuit', kohana::lang('circuit.back'), array('class' => 'button'));
	?>
</div>
	
<div class="column3">
	<?php
	$nbr_chocobos = count($circuit->chocobos);
	if ($nbr_chocobos > 0) 
	{
	?>
		<div class="title">Départ</div>
		<table class="circuitInside">
			<?php foreach ($circuit->chocobos as $chocobo): ?>
				<tr>
					<td><?= html::image('images/icons/normal_sepia.jpg') ?></td>
					<td class="icons"><?= $chocobo->display_image('mini'); ?></td>
					<td>
						<?php echo $chocobo->vignette(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php 
	} 
	?>
</div>

<div class="clearBoth"></div>
<?= $wave ?>
