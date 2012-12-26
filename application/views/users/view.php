<h2>
	<?php echo HTML::anchor('users', __('Jockeys')) ?> &rarr; 
	<?php echo $user->username ?>
</h2>

<div class="nav">
	<?php
		echo HTML::anchor('#/informations', 'Informations');
		echo HTML::anchor('#/chocobos', 'Ecurie');
		echo HTML::anchor('#/achievements', 'Succès');
		if ($user->id == $u->id) {
			echo HTML::anchor('users/'.$user->id.'/edit', 'Préférences'); 
	    }
	?>
</div>

<div class="section" id="informations">

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
			echo HTML::image('images/theme/gil.gif');
			?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Nombre de chocobos</td>
		<td>
		<?php
			echo count($user->chocobos->find_all()) . ' /' . $user->get_boxes();
		?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Succès</td>
		<td>
		<?php
			$nbr_titles = ORM::factory('title')->count_all();
			echo $user->successes->count_all() . ' /' . $nbr_titles;
		?>
		</td>
		<td></td>
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

	<?php foreach($user->chocobos->find_all() as $n => $chocobo): ?>
	<tr>
		<td>
			<?php echo $chocobo->vignette() ?>
		</td>

		<?php if ($user->id == $u->id): ?>
		<td>
			<?php 
			if ($chocobo->id != $c->id)
			{
				echo HTML::anchor('chocobos/'.$chocobo->id.'/change', 'Choisir', array('class' => 'button blue'));
			}
			?>
		</td>
		<?php endif; ?>

		<td>
			<?php echo HTML::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</div>

<div class="section" id="achievements">

<table class="table1" id="successes">
	<thead>
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len100">Part</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($user->successes->find_all() as $success): ?>
		<tr>
			<td><?php echo __('success.'.$success->title->name.'.name') ?></td>
			<td><?php echo ceil(($success->title->nbr_users * 100) / count(Model_User::get_users())) ?></td>
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
