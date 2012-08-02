<style>
.progress {height: 3px;}
.p-green {background-color: #090;}
.p-yellow {background-color: #900;}
.p-red {background-color: #900;}
.p-grey {background-color: #999;}
</style>

<?php $mine = ($chocobo->user->id == $user->id) ?>

<?php
//////////////////
///// INFORMATIONS
//////////////////
?>

<h2>Informations</h2>

<table class="table1">

	<tr>
		<th>Champ</th>
		<th>Valeur</th>
		<th></th>
	</tr>

	<tr class="tr1">
		<td class="len150">Nom</td>
		<td class="lenmax"><?php echo $chocobo->name ?></td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Couleur</td>
		<td class="lenmax"><?php echo ucfirst($chocobo->display_colour('zone')) ?></td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Job</td>
		<td class="lenmax">Chocobo</td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">classe</td>
		<td class="lenmax"><?php echo $chocobo->display_classe() ?></td>
		<td class="len100">
			<?php echo progress::display($chocobo->classe, 5, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Niveau</td>
		<td class="lenmax"><?php echo $chocobo->level . ' /' . $chocobo->lvl_limit ?></td>
		<td class="p-wrapper len100">
			<?php echo progress::display($chocobo->level, $chocobo->lvl_limit, 100) ?>
		</td>
	</tr>

	<tr class="tr1">
		<td class="len150">Expérience</td>
		<td class="lenmax"><?php echo $chocobo->xp ?>xp /100</td>
		<td class="len100 p-wrapper4">
			<?php echo progress::display($chocobo->xp, 100, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Côte</td>
		<td class="lenmax"><?php echo $chocobo->display_fame() ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display(1 - $chocobo->fame + 0.01, 1, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Vitesse max</td>
		<td class="lenmax"><?php echo $chocobo->max_speed ?> km/h</td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->max_speed, 175, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Prix</td>
		<td class="lenmax"><?php echo $chocobo->get_price() . html::image('images/icons/gils.png', array('class' => 'icon4')) ?></td>
		<td class="len100 p-wrapper">
			<?php
			if ($chocobo->user_id == $user->id and count($chocobo->user->chocobos) > 1)
			{
				$texte = "Confirmer ?";
				echo html::anchor('chocobo/sale/' . $chocobo->id, 'Vendre', array('class' => 'button red', 'onclick' => "return confirm('$texte');"));
			}
			?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Naissance</td>
		<td class="lenmax"><?php echo date::display($chocobo->birthday) ?></td>
		<td class="len100"></td>
	</tr>
	
</table>

<h2>Caractéristiques</h2>

<table class="table1">

	<tr>
		<th>Champ</th>
		<th>Valeur</th>
		<th></th>
	</tr>

	<tr class="tr1">
		<td class="len150">Vitesse</td>
		<td class="lenmax">
			<?php
			echo $chocobo->attr('speed');
			if ($mine and $chocobo->points > 0)
			{
				echo html::anchor(
					'chocobo/aptitude_up/speed', 
					html::image('images/theme/plus.jpg', array('class' => 'raise'))
				);
			}
			?>
		</td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->attr('speed'), 175, 100) ?>
		</td>
	</div>
	
	<tr class="tr1">
		<td class="len150">Endurance</td>
		<td class="lenmax">
			<?php
			echo $chocobo->attr('endur');
			if ($mine and $chocobo->points > 0)
			{
				echo html::anchor(
					'chocobo/aptitude_up/endur', 
					html::image('images/theme/plus.jpg', array('class' => 'raise'))
				);
			}
			?>
		</td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->attr('endur'), 175, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Intelligence</td>
		<td class="lenmax">
			<?php
			echo $chocobo->attr('intel'); 
			if ($mine and $chocobo->points > 0)
			{
				echo html::anchor(
					'chocobo/aptitude_up/intel', 
					html::image('images/theme/plus.jpg', array('class' => 'raise'))
				);
			}
			?>
		</td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->attr('intel'), 175, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">PA</td>
		<td class="lenmax"><?php echo $chocobo->points ?></td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">PL</td>
		<td class="lenmax"><?php echo $chocobo->pl ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->pl, $chocobo->attr('pl_limit'), 100) ?>
		</td>
	</tr>
		
	<tr class="tr1">
		<td class="len150">HP</td>
		<td class="lenmax"><?php echo $chocobo->hp ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->hp, $chocobo->attr('hp_limit'), 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">MP</td>
		<td class="lenmax"><?php echo $chocobo->mp ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->mp, $chocobo->attr('mp_limit'), 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">PC</td>
		<td class="lenmax"><?php echo $chocobo->attr('pc_limit') ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->attr('pc_limit'), $chocobo->attr('pc_limit'), 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Rage</td>
		<td class="lenmax"><?php echo $chocobo->rage ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->rage, $chocobo->attr('rage_limit'), 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Résistance</td>
		<td class="lenmax"><?php echo $chocobo->attr('resistance') ?></td>
		<td class="len100"></td>
	</tr>
	
</table>
