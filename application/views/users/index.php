<h2>Jockeys</h2>

<div class="nav">
	<?php echo HTML::anchor('#/index', 'Index') ?>
</div>

<table id="users" class="table1">
	<thead>
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len100">Dernière connexion</th>
			<th></th>
			<th class="len100"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
		<tr>
			<td><?php echo $user->username ?></td>
			<td class="date"><?php echo $user->connected ?></td>
			<td><?php echo date::display($user->connected) ?></td>
			<td><?php echo html::anchor('users/' . $user->id, 'Voir', array('class' => 'button green')) ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(function(){

	init_nav('#/index');
	
	$('#users').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aaSorting": [ [1,'desc'] ],
		"aoColumnDefs": [ 
            {
                "fnRender": function ( oObj, sVal ) {
                    return oObj.aData[2];
                },
                "bUseRendered": false,
                "aTargets": [ 1 ]
            },
            { "bVisible": false,  "aTargets": [ 2 ] }
        ],
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>