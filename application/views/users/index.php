<h2>Nombre de jockeys inscrits : <?php echo count($users) ?></h2>

<table id="users" class="table1">
	<thead>
		<tr>
			<th>Nom</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
		<tr class="tr1">
			<td class="lenmax"><?php echo $user->username ?></td>
			<td class="len100"><?php echo html::anchor('users/' . $user->id, 'Voir', array('class' => 'button green')) ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(function(){
	
	$('#users').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>