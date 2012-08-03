<div class="nav">
	<?php echo html::anchor('#/informations', 'Informations') ?>
	<?php echo html::anchor('#/chocobos', 'Ecurie') ?>
</div>

<div class="section" id="informations">

<?php 
if ($user->id == $u->id)
{
	echo html::anchor('/user/edit', 'Modifier', array('class'=>'button blue fright'));
}
?>

<div class="clearright"></div>

<table class="table1">

	<tr class="first">
		<th class="len150">Champ</th>
		<th class="lenmax">Valeur</th>
		<th class="len100"></th>
	</tr>

	<tr>
		<td>Nom</td>
		<td>
			<?php echo $user->username ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Statut</td>
		<td>
			<?php echo $user->role() ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Genre</td>
		<td>
			<?php echo $user->display_gender() ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Langue</td>
		<td>
			<?php echo $user->locale ?>
		</td>
		<td></td>
	</tr>

	<?php if (!empty($user->birthday)) { ?>
	<tr>
		<td>Naissance</td>
		<td>
			<?php echo $user->birthday ?>
		</td>
		<td></td>
	</tr>
	<?php } ?>

	<tr>
		<td>Argent</td>
		<td>
			<?php
			echo $user->gils;
			echo html::image('images/theme/gil.gif');
			?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Chocobos</td>
		<td>
		<?php
			echo count($user->chocobos) . '<small>/' . $user->get_boxes() . '</small>';
		?>
		</td>
		<td>
			<?php
			echo html::anchor('users/' . $user->id . '/chocobos', 'Voir', array('class' => 'button green'));
			?>
		</td>
	</tr>
	<tr>
		<td>Succès</td>
		<td>
		<?php
			$nbr_titles = ORM::factory('title')->count_all();
			echo count($user->successes) . '<small>/' . $nbr_titles . '</small>';
		?>
		</td>
		<td>
			<?php
			echo html::anchor('users/' . $user->id . '/achievements', 'Voir', array('class' => 'button green'));
			?>
		</td>
	</tr>
	<tr>
		<td>Inscrit</td>
		<td>
			<?php echo date::display($user->created) ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Connecté</td>
		<td>
			<?php echo date::display($user->updated) ?>
		</td>
		<td></td>
	</tr>

</table>
</div>
	
<div class="section" id="chocobos">
	
<table class="table1">
	<tr class="first">
		<th class="lenmax">Nom</th>
		<?php if ($user->id == $u->id): ?><th class="len100"></th><?php endif; ?>
		<th class="len100"></th>
	</tr>

	<?php foreach($user->chocobos as $n => $chocobo): ?>
	<tr>
		<td>
			<?php echo $chocobo->vignette() ?>
		</td>

		<?php if ($user->id == $u->id): ?>
		<td>
			<?php 
			if ($chocobo->id != $c->id)
			{
				echo html::anchor('chocobo/change/' . $chocobo->id, 'Choisir', array('class' => 'button blue'));
			}
			?>
		</td>
		<?php endif; ?>

		<td>
			<?php echo html::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</div>

<script>
$(function(){

	init_nav('#/informations');

});
</script>
