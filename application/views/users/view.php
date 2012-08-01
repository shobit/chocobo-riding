<?php if ($section == ''): ?>
<h2>Informations</h2>

<?php 
if ($user->id == $u->id)
{
	echo html::anchor('/user/edit', 'Modifier', array('class'=>'button blue fright'));
}
?>

<div class="clearright"></div>

<table class="table1">

	<tr>
		<th>Champ</th>
		<th>Valeur</th>
		<th></th>
	</tr>

	<tr class="tr1">
		<td class="len150">Nom</td>
		<td class="lenmax">
			<?php echo $user->username ?>
		</td>
		<td class="len100"></td>
	</tr>
	<tr class="tr1">
		<td class="len150">Statut</td>
		<td class="lenmax">
			<?= $user->role() ?>
		</td>
		<td class="len100"></td>
	</tr>
	<tr class="tr1">
		<td class="len150">Genre</td>
		<td class="lenmax">
			<?= $user->display_gender() ?>
		</td>
		<td class="len100"></td>
	</tr>
	<tr class="tr1">
		<td class="len150">Langue</td>
		<td class="lenmax">
		<?= $user->locale ?>
		</td>
		<td class="len100"></td>
	</tr>
	<?php if (!empty($user->birthday)) { ?>
	<tr class="tr1">
		<td class="len150">Naissance</td>
		<td class="lenmax">
			<?= $user->birthday ?>
		</td>
		<td class="len100"></td>
	</tr>
	<?php } ?>
	<tr class="tr1">
		<td class="len150">Argent</td>
		<td class="lenmax">
			<?php
			echo $user->gils;
			echo html::image('images/theme/gil.gif');
			?>
		</td>
		<td class="len100"></td>
	</tr>
	<tr class="tr1">
		<td class="len150">Chocobos</td>
		<td class="lenmax">
		<?php
			echo count($user->chocobos) . '<small>/' . $user->get_boxes() . '</small>';
		?>
		</td>
		<td class="len100">
			<?php
			echo html::anchor('users/' . $user->id . '/chocobos', 'Voir', array('class' => 'button green'));
			?>
		</td>
	</tr>
	<tr class="tr1">
		<td class="len150">Succès</td>
		<td class="lenmax">
		<?php
			$nbr_titles = ORM::factory('title')->count_all();
			echo count($user->successes) . '<small>/' . $nbr_titles . '</small>';
		?>
		</td>
		<td class="len100">
			<?php
			echo html::anchor('users/' . $user->id . '/achievements', 'Voir', array('class' => 'button green'));
			?>
		</td>
	</tr>
	<tr class="tr1">
		<td class="len150">Inscrit</td>
		<td class="lenmax">
			<?php echo date::display($user->created) ?>
		</td>
		<td class="len100"></td>
	</tr>
	<tr class="tr1">
		<td class="len150">Connecté</td>
		<td class="lenmax">
			<?php echo date::display($user->updated) ?>
		</td>
		<td class="len100"></td>
	</tr>

</table>
<?php endif; ?>
	
<?php if ($section == 'chocobos'): ?>
<h2>Chocobos dans l'écurie : <?php echo count($user->chocobos).'/'.$user->get_boxes() ?></h2>
	
<table class="table1">
	<tr>
		<th>Nom</th>
		<?php if ($user->id == $u->id): ?><th></th><?php endif; ?>
		<th></th>
	</tr>

	<?php foreach($user->chocobos as $n => $chocobo): ?>
	<tr class="tr1">
		<td class="lenmax">
			<?php echo $chocobo->vignette() ?>
		</td>

		<?php if ($user->id == $u->id): ?>
		<td class="len100">
			<?php 
			if ($chocobo->id != $c->id)
			{
				echo html::anchor('chocobo/change/' . $chocobo->name, 'Choisir', array('class' => 'button blue'));
			}
			?>
		</td>
		<?php endif; ?>

		<td class="len100">
			<?php echo html::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
