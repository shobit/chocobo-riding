<style>
.type {
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
	text-transform: uppercase;
	background-color: #333;
	color: #fff;
	font-size: 10px;
	font-weight: bold;
	padding: 3px 6px;
	margin-right: 10px;
	text-shadow: 0 1px 1px rgba(50,50,50,0.5)
}
.added {background-color: #7C29A9; color: #fff;}
.fixed {background-color: #226EC7; color: #fff;}
.improved {background-color: #97B931; color: #fff;}
.new {background-color: #7c29a9; color: #fff;}

.wrapper-type {text-align: center; vertical-align: top;}

.title {font-weight: bold; line-height: 18px;}
.content {line-height: 18px;}
.date {font-size: 10px; color: #999; line-height: 18px;}
</style>

<h2>Améliorations</h2>

<table class="table1">
	<tr>
		<th>Type</th>
		<th>Contenu</th>
		<?php if ($user->has_role('admin')): ?><th></th><?php endif; ?>
	</tr>

	<?php foreach ($updates as $update): ?>
		<tr class="tr1">
			<td class="wrapper-type len100">
				<span class="type <?php echo $update->type ?>"><?php echo $update->type ?></span>
			</td>
			<td class="lenmax">
				<div class="title"><?php echo $update->title ?></div>
				<div class="date"><?php echo date::display(strtotime($update->date)) ?></div>
			</td>
			<?php if ($user->has_role('admin')): ?>
				<td class="len100">
				<?php echo html::anchor('update/edit/' . $update->id, 'Modifier', array('class' => 'button fancybox fancybox.ajax')) ?>
				</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>

<?php if ($user->has_role('admin')): ?>
<div class="actions">
	<div>
		<?php
		echo html::image('images/icons/arrow_right.gif', array('class' => 'icon4')) . ' ';
		echo html::anchor('update/edit/0', 'Ajouter une note de version', array('class' => 'fancybox fancybox.ajax'));
		?>
	</div>
</div>
<?php endif; ?>

<script>
$(function(){
	
	$('.update').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});	

})
</script>
