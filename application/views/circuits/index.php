<h1><?= Kohana::lang('location.index.title') ?></h1>
<div id="prelude"><?= Kohana::lang('location.index.prelude') ?></div>

<table class="forum">
	<tr class="top">
		<th></th>
		<th>Nom de la course</th>
		<th>Classe</th>
		<th>Vitesse</th>
		<th>Intelligence</th>
		<th>Endurance</th>
	</tr>
<?php
foreach($locations as $location) 
{
	?>
	<tr>
		<td><?= $location->display_image('mini') ?></td>
		<td><?= $location->name() ?> 
			<?php 
				$nbr_circuits = count($location->circuits);
				if ($nbr_circuits > 0) echo '<small>('.$nbr_circuits.' en cours)</small>'; 
			?>
		</td>
		
		<td style="text-align:center;"><?= html::image("images/classes/".$location->classe.".gif") ?></td>
		<td style="text-align:center;"><?= $location->speed ?></td>
		<td style="text-align:center;"><?= $location->intel?></td>
		<td style="text-align:center;"><?= $location->endur ?></td>
	</tr>
	<?php
}
?>
</table>
