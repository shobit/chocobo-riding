<h2>Nombre de discussions : <?php echo count($discussions) ?></h2>

<?php echo html::anchor('discussions/new', 'Créer', array('class' => 'button blue fright')) ?>
<div class="clearright;"></div>

<table id="discussions" class="table1">

	<thead>
		<tr>
			<th>Destinataire</th>
			<th>Dernier message</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ($discussions as $n => $discussion) 
		{
			$discussion = ORM::factory('discussion', $discussion->id);
			$receiver = $discussion->receiver($user->id);
		    $nbr_messages = count($discussion->messages) - 1;
			$notified = false;
		?>
		<tr class="tr1">

			<td class="lenmax">
				<div class="title">
					<span class="nbr_messages"><?php echo $nbr_messages ?></span> 
					<?php echo $receiver->username ?>
				</div>
			</td>

			<td class="date len100">
				<?php echo date::display($discussion->updated) ?>
			</td>

			<td class="len100">
				<?php echo html::anchor('discussions/' . $discussion->id, 'Lire', array('class' => 'button green')) ?>
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
		"aaSorting": [ [1,'desc'] ],
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

});
</script>
