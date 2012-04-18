<style>
	.circuits {width: 500px; border: 1px solid #CECECE; border-collapse: collapse;}
	.circuits th {text-align: center; font-size: 10px;}
	.circuits td {text-align: center; border: 1px solid #CECECE;}
	.circuit {height: 30px;}
	.circuit .name {width: 250px;}
	.circuit .length {width: 50px;}
	.circuit .pl {width: 50px;}
	.circuit .bonus {width: 100px;}
	.circuit .nbr_chocobos {width: 50px;}
</style>

<h1><?php echo Kohana::lang('location.index.title') ?></h1>
<div id="prelude"><?php echo Kohana::lang('location.index.prelude') ?></div>

<table class="circuits">
	<tr>
		<th>Nom</th>
		<th>Distance</th>
		<th>PL</th>
		<th>Courses</th>
		<th>Classe</th>
	</tr>
	<?php foreach ($circuits as $circuit): ?>
	<tr class="circuit">
		<td class="name"><?php echo html::anchor('admin/circuits/' . $circuit->id . '/edit', $circuit->name()) ?></td>
		<td class="length"><?php echo $circuit->length ?></td>
		<td class="pl"><?php echo $circuit->pl ?></td>
		<td class="nb_races">
			<?php 
				$nb_races = count($circuit->races);
				if ($nb_races > 0) echo '<small>('.$nb_races.' en cours)</small>'; 
			?>
		</td>
		
		<td class="classe"><?= html::image("images/classes/".$circuit->classe.".gif") ?></td>
	</tr>
	<?php endforeach; ?>
</table>
