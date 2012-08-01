<h2>Améliorations</h2>

<?php 
if ($user->has_role('admin'))
{
	echo html::anchor('update/edit/0', 'Ajouter', array('class' => 'button blue fright fancybox fancybox.ajax'));
	echo '<div class="clearright"></div>';
}
?>


<table class="table1">
	<tr>
		<th>Type</th>
		<th>Contenu</th>
		<th>Date</th>
		<?php if ($user->has_role('admin')): ?><th></th><?php endif; ?>
	</tr>

	<?php foreach ($updates as $update): ?>
		<tr class="tr1">
			<td class="wrapper-type len100">
				<span class="type <?php echo $update->type ?>"><?php echo $update->type ?></span>
			</td>
			<td class="lenmax">
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
			<td class="date len100">
				<?php echo date::display(strtotime($update->date)) ?>
			</td>
			<?php if ($user->has_role('admin')): ?>
				<td class="len100">
				<?php echo html::anchor('update/edit/' . $update->id, 'Modifier', array('class' => 'button green fancybox fancybox.ajax')) ?>
				</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>

<script>
$(function(){
	
	$('.update').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});	

})
</script>
