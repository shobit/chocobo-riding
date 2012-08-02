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

	<tr class="first">
		<th class="len150">Champ</th>
		<th class="lenmax">Valeur</th>
		<th class="len100"></th>
	</tr>

	<tr>
		<td>Jockey</td>
		<td><?php echo $chocobo->user->username ?></td>
		<td><?php echo html::anchor('users/' . $chocobo->user_id, 'Voir', array('class' => 'button green')) ?></td>
	</tr>

	<tr>
		<td>Nom</td>
		<td><?php echo $chocobo->name ?></td>
		<td></td>
	</tr>

	<tr>
		<td>Genre</td>
		<td><?php echo $chocobo->display_gender('zone') ?></td>
		<td></td>
	</tr>
	
	<tr>
		<td>Couleur</td>
		<td><?php echo ucfirst($chocobo->display_colour('zone')) ?></td>
		<td></td>
	</tr>
	
	<tr>
		<td>Job</td>
		<td>Chocobo</td>
		<td></td>
	</tr>
	
	<tr>
		<td>Classe</td>
		<td><?php echo $chocobo->display_classe() ?></td>
		<td>
			<?php echo progress::display($chocobo->classe, 5, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Niveau</td>
		<td><?php echo $chocobo->level . ' /' . $chocobo->lvl_limit ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->level, $chocobo->lvl_limit, 100) ?>
		</td>
	</tr>

	<tr>
		<td>Expérience</td>
		<td><?php echo $chocobo->xp ?>/100 xp</td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->xp, 100, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Côte</td>
		<td><?php echo $chocobo->display_fame() ?></td>
		<td class="p-wrapper">
			<?php echo progress::display(1 - $chocobo->fame + 0.01, 1, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Vitesse max</td>
		<td><?php echo $chocobo->max_speed ?> km/h</td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->max_speed, 175, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Prix</td>
		<td><?php echo $chocobo->get_price() . html::image('images/icons/gils.png', array('class' => 'icon4')) ?></td>
		<td class="p-wrapper">
			<?php
			if ($chocobo->user_id == $user->id and count($chocobo->user->chocobos) > 1)
			{
				$texte = "Confirmer ?";
				echo html::anchor('chocobo/sale/' . $chocobo->id, 'Vendre', array('class' => 'button red', 'onclick' => "return confirm('$texte');"));
			}
			?>
		</td>
	</tr>
	
	<tr>
		<td>Naissance</td>
		<td><?php echo date::display($chocobo->birthday) ?></td>
		<td></td>
	</tr>
	
</table>

<h2>Caractéristiques</h2>

<table class="table1">

	<tr class="first">
		<th class="len150">Champ</th>
		<th class="lenmax">Valeur</th>
		<th class="len100"></th>
	</tr>

	<tr>
		<td>Vitesse</td>
		<td>
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
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->attr('speed'), 175, 100) ?>
		</td>
	</div>
	
	<tr>
		<td>Endurance</td>
		<td>
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
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->attr('endur'), 175, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Intelligence</td>
		<td>
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
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->attr('intel'), 175, 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>PA</td>
		<td><?php echo $chocobo->points ?></td>
		<td></td>
	</tr>
	
	<tr>
		<td>PL</td>
		<td><?php echo $chocobo->pl ?> /<?php echo $chocobo->attr('pl_limit') ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->pl, $chocobo->attr('pl_limit'), 100) ?>
		</td>
	</tr>
		
	<tr>
		<td>HP</td>
		<td><?php echo $chocobo->hp ?> /<?php echo $chocobo->attr('hp_limit') ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->hp, $chocobo->attr('hp_limit'), 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>MP</td>
		<td><?php echo $chocobo->mp ?> /<?php echo $chocobo->attr('mp_limit') ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->mp, $chocobo->attr('mp_limit'), 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>PC</td>
		<td><?php echo $chocobo->attr('pc_limit') ?> /<?php echo $chocobo->attr('pc_limit') ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->attr('pc_limit'), $chocobo->attr('pc_limit'), 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Rage</td>
		<td><?php echo $chocobo->rage ?> /<?php echo $chocobo->attr('rage_limit') ?></td>
		<td class="p-wrapper">
			<?php echo progress::display($chocobo->rage, $chocobo->attr('rage_limit'), 100) ?>
		</td>
	</tr>
	
	<tr>
		<td>Résistance</td>
		<td><?php echo $chocobo->attr('resistance') ?></td>
		<td></td>
	</tr>
	
</table>
