<style>
	.loading {font-size: 10px; color: #999; font-style: italic; display: none;}
	
</style>

<h1><?php echo Kohana::lang('race.view_start.title') . ' ' . html::image('images/classes/' . $race->circuit->classe . '.gif'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('location.' . $race->circuit->code . '.prelude'); ?></div>

<?php echo new View("races/description", array('race' => $race)); ?>
	
<div style="float:right;">
	<?php 
	$display_on = ' style="display:inline;"';
	$display_off = ' style="display:none;"';
	
	$registered = ($chocobo->race_id === $race->id);
	
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
		+ <?php echo html::anchor('races/' . $race->id . '/register', Kohana::lang('race.register'), array('id' => 'a-subscribe')); ?> 
		<span class="loading">en cours</span>
	</span>
	
	<span id="unregister"<?php echo $unregister; ?>>
		- <?php echo html::anchor('races/' . $race->id . '/unregister', Kohana::lang('race.unregister'), array('id' => 'a-unsubscribe')); ?> 
		<span class="loading">en cours</span>
	</span>
	
	<?php
	echo html::anchor('races/' . $race->id, Kohana::lang('race.refresh'), array('class' => 'button'));
	
	echo html::anchor('races', Kohana::lang('race.back'), array('class' => 'button'));
	?>
</div>
	
<div class="column3">
	<?php
	$nbr_chocobos = count($race->chocobos);
	if ($nbr_chocobos > 0) 
	{
	?>
		<div class="title">Départ</div>
		<table class="circuitInside">
			<?php foreach ($race->chocobos as $chocobo): ?>
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
