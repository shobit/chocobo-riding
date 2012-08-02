<h2>Nombre de chocobos inscrits : <?php echo count($chocobos) ?></h2>

<table id="chocobos" class="table1">
	<thead>
		<tr>
			<th>Nom</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($chocobos as $chocobo): ?>
		<tr class="tr1">
			<td class="lenmax"><?php echo $chocobo->name ?></td>
			<td class="len100"><?php echo html::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?></td>
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