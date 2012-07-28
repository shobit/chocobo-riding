<style>
	.results {width: 780px; border: 1px solid #CECECE; border-collapse: collapse;}
	.results th {text-align: center; font-size: 10px;}
	.results td {text-align: center; border: 1px solid #CECECE;}
	.result {height: 30px;}
	.result.not_seen {background-color: #fee;}
	.result .name {width: 250px;}
	.result .length {width: 50px;}
	.result .pl {width: 50px;}
	.result .bonus {width: 100px;}
	.result .nbr_chocobos {width: 50px;}
	.result .date {width: 180px;}
	.result .delete {width: 100px;}
</style>

<h2>Début de la prochaine course dans 
	<span id="<?php echo $races[0]->id ?>">--:--</span> 
	<?php echo html::image('images/icons/hour2.png') ?>
</h2>

<script>
	decompte(
		'<?php echo $races[0]->id  ?>', 
		'<?php echo ($races[0]->start - time()) ?>', 
		'<?php echo Kohana::lang('race.index.finished') ?>',
		false
	);
</script>

<table class="table1">
	<tr>
		<th>Nom</th>
		<th>PL</th>
		<th></th>
	</tr>
	<?php foreach ($races as $race): ?>
	<tr class="tr1">
		<td class="lenmax"><?php echo $race->circuit->name() ?></td>
		<td class="len150"><?php echo $race->circuit->pl ?></td>
		<td class="len100"><?php echo html::anchor('races/' . $race->id, 'Voir', array('class' => 'button')) ?></td>
	</tr>
	<?php endforeach; ?>
</table>


<?php if (count($results) > 0): ?>
	<h2>Historique des courses</h2>

	<table class="table1">
		<tr>
			<th>Nom</th>
			<th></th>
			<th></th>
		</tr>
		<?php foreach ($results as $result): 
			$not_seen = ( ! $result->seen) ? ' not_seen': '';
			?>
			<tr class="tr1 result<?php echo $not_seen ?>" id="result<?php echo $result->race->id ?>">
				<td class="lenmax"><?php echo $result->race->circuit->name() ?></td>
				<td class="len100">
					<?php
						echo html::anchor('', 'Supprimer', array('class' => 'button delete_result', 'id' => 'race' . $result->race->id));
					?>
				</td>
				<td class="len100">
					<?php
						echo html::anchor('races/' . $result->race->id, 'Voir', array('class' => 'button'));
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>

<script>

$(function(){
	
	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	$('.delete_result')
		.click(function(){
			var tr = $(this).closest('tr');
			var race_id = tr.attr('id').substring(6);
			$(this).hide();
			$.post(baseUrl + 'races/delete', {'id': race_id}, function(data){
				if (data.success) {
					tr.slideUp();
				}
			});
			return false;
		});
});

</script>
