<h2>
	<?php echo HTML::anchor('chocobos', __('Chocobos')) ?>
</h2>

<div class="nav">
	<?php echo HTML::anchor('#/index', 'Index') ?>
</div>

<table id="chocobos" class="table1">
	<thead>
		<tr class="first">
			<th class="len150">Jockey</th>
			<th class="lenmax">Nom</th>
			<th class="len100">Niveau</th>
			<th></th>
			<th class="len100"></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($chocobos as $c): 
			$chocobo = ORM::factory('chocobo', $c->id);
		?>
		<tr>
			<td class="minor"><?php echo $chocobo->user->username ?></td>
			<td><?php echo $chocobo->vignette() ?></td>
			<td class="minor"><?php echo $chocobo->level ?></td>
			<td><?php echo $chocobo->level ?> /<?php echo $chocobo->lvl_limit ?></td>
			<td><?php echo html::anchor('chocobos/' . $chocobo->id, 'Voir', array('class' => 'button green')) ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(function(){

	init_nav('#/index');
	
	$('#chocobos').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aaSorting": [ [1,'asc'] ],
		"aoColumnDefs": [ 
            {
                "fnRender": function ( oObj, sVal ) {
                    return oObj.aData[3];
                },
                "bUseRendered": false,
                "aTargets": [ 2 ]
            },
            { "bVisible": false,  "aTargets": [ 3 ] }
        ],
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>