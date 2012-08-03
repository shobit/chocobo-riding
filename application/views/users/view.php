<div class="nav">
	<?php echo html::anchor('#/informations', 'Informations') ?>
	<?php echo html::anchor('#/chocobos', 'Ecurie') ?>
	<?php echo html::anchor('#/achievements', 'Succès') ?>
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
			echo count($user->chocobos) . ' /' . $user->get_boxes();
		?>
		</td>
		<td>
			<?php
			echo html::anchor('#/chocobos', 'Voir', array('class' => 'button green'));
			?>
		</td>
	</tr>
	<tr>
		<td>Succès</td>
		<td>
		<?php
			$nbr_titles = ORM::factory('title')->count_all();
			echo count($user->successes) . ' /' . $nbr_titles;
		?>
		</td>
		<td>
			<?php
			echo html::anchor('#/achievements', 'Voir', array('class' => 'button green'));
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

<div class="section" id="achievements">

<?php
$successes = $user->successes;
$nb_users = User_Model::get_nbr_players();
?>

<table class="table1" id="successes">
	<thead>
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len100">Part</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($successes as $success): ?>
		<tr>
			<td><?php echo Kohana::lang("success." . $success->title->name . '.name') ?></td>
			<td><?php echo ceil(($success->title->nbr_users * 100) / $nb_users) ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

</div>

<script>
$(function(){

	init_nav('#/informations');

	$('#successes').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aaSorting": [ [1,'desc'] ],
		"aoColumnDefs": [ 
            {
                "fnRender": function ( oObj, sVal ) {
                    return sVal + '%';
                },
                "bUseRendered": false,
                "aTargets": [ 1 ]
            }
        ],
		"oLanguage": {
			"sUrl": baseUrl + "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>
