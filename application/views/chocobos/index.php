<h2>Nombre de chocobos inscrits : <?php echo count($chocobos) ?></h2>

<table id="chocobos" class="table1">
	<thead>
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len100"></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($chocobos as $c): 
			$chocobo = ORM::factory('chocobo', $c->id);
		?>
		<tr>
			<td><?php echo $chocobo->name ?></td>
			<td><?php echo html::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(function(){
	
	$('#chocobos').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>