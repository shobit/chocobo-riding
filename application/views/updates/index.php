<h2>Améliorations</h2>

<?php 
if ($user->has_role('admin'))
{
	echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'button blue fright fancybox fancybox.ajax'));
	echo '<div class="clearright"></div>';
}
?>


<table class="table1" id="updates">
	<thead>
		<tr class="first">
			<th class="len100">Type</th>
			<th class="lenmax">Contenu</th>
			<th class="len100">Date</th>
			<th></th>
			<?php if ($user->has_role('admin')): ?><th class="len100"></th><?php endif; ?>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($updates as $update): ?>
		<tr>
			<td class="wrapper-type">
				<span class="type <?php echo $update->type ?>"><?php echo $update->type ?></span>
			</td>
			<td>
				<?php 
				if (empty($update->content))
				{
					echo $update->title;
				}
				else
				{
					echo vignette::display($update->title, $update->content);
				}
				?>
			</td>
			<td class="date">
				<?php echo strtotime($update->date) ?>
			</td>
			<td class="date">
				<?php echo date::display(strtotime($update->date)) ?>
			</td>
			<?php if ($user->has_role('admin')): ?>
				<td>
				<?php echo html::anchor('update/edit/' . $update->id, 'Modifier', array('class' => 'button green fancybox fancybox.ajax')) ?>
				</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(function(){

	$('#updates').dataTable({
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
			"sUrl": baseUrl + "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

})
</script>
