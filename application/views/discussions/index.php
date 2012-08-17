<h2>Nombre de discussions : <?php echo count($discussions) ?></h2>

<?php if ($user): ?>
	<?php echo html::anchor('discussions/new', 'Créer', array('class' => 'button blue fright')) ?>
	<div class="clearright;"></div>
<?php endif; ?>

<table id="discussions" class="table1">
	
	<thead>
		<tr>
			<th class="len100">Type</th>
			<th class="lenmax">Titre</th>
			<th class="len100">Dernier message</th>
			<th></th>
			<th class="len100"></th>
		</tr>
	</thead>

	<tbody>
	<?php
	foreach ($discussions as $discussion) 
	{
		$messages = $discussion->messages->find_all();
		$nbr_messages = count($messages);
		
		$not_seen = '';

		if ($user)
		{
			$nbr_notifications = $user->get_notifications($discussion->id);
			$not_seen = ($nbr_notifications > 0) ? ' not_seen': '';		
		}
	?>
		
	<tr class="discussion<?php echo $not_seen ?>" id="discussion<?php echo $discussion->id ?>">	
		<td class="type2">
			<?php echo $discussion->type ?>
		</td>
			
		<td class="title">
			<span class="nbr_messages">
				<?php echo ($nbr_messages - 1) ?>
			</span> 
			<?php if ($user AND $nbr_notifications > 0): ?>
				<span class="nbr_notifications">
					+<?php echo $nbr_notifications ?>
				</span>
			<?php endif; ?>
			<?php echo $discussion->title ?>
		</td>

		<td class="date">
			<?php echo $discussion->updated ?>
		</td>

		<td>
			<?php echo Date::display($discussion->updated) ?>
		</td>
		
		<td>
			<?php echo HTML::anchor('discussions/'.$discussion->id, 'Lire', array('class' => 'button green')) ?>
		</td>
	</tr>
		
	<?php
	}
	?>
	</tbody>

</table>

<script>
$(document).ready(function(){

	$('#discussions').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aaSorting": [ [2,'desc'] ],
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
