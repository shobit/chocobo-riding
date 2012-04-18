<style>
	.col1 {
		float: right;
		width: 175px;
		padding: 3px;
		text-align: center;
	}

	.races {width: 500px; border: 1px solid #CECECE; border-collapse: collapse;}
	.races th {text-align: center; font-size: 10px;}
	.races td {text-align: center; border: 1px solid #CECECE;}
	.race {height: 30px;}
	.race .name {width: 250px;}
	.race .length {width: 50px;}
	.race .pl {width: 50px;}
	.race .xp {width: 50px;}
	.race .gils {width: 50px;}
	.race .nbr_chocobos {width: 50px;}
	
	.results {width: 735px; border: 1px solid #CECECE; border-collapse: collapse;}
	.results th {text-align: center; font-size: 10px;}
	.results td {text-align: center; border: 1px solid #CECECE;}
	.result {height: 30px;}
	.result.not_seen {background-color: #fee;}
	.result .name {width: 250px;}
	.result .length {width: 50px;}
	.result .pl {width: 50px;}
	.result .bonus {width: 100px;}
	.result .nbr_chocobos {width: 50px;}
	.result .date {width: 135px;}
	.result .options {width: 100px;}
</style>

<h1><?php echo Kohana::lang('race.index.title') . ' ' . html::image("images/classes/$classe.gif") ?></h1>
<div id="prelude"><?php echo Kohana::lang('race.index.prelude') ?></div>

<h2>Courses officielles</h2>

<div class="circuit">
	<div class="col1">
		<?php echo html::image('images/pages/circuits.jpg', array('class'=>'location')) ?>
		
		<br /><?php echo html::image('images/icons/hour2.png'); ?>
		<span style="display: inline;" id="<?php echo $races[0]->id; ?>">--:--</span>
				
		<script language=JavaScript>
			decompte(
				'<?php echo $races[0]->id  ?>', 
				'<?php echo ($races[0]->start - time()) ?>', 
				'<?php echo Kohana::lang('race.index.finished') ?>',
				false
			);
		</script>
	</div>
	
	<table class="races">
		<tr>
			<th>Nom</th>
			<th>Distance</th>
			<th>PL</th>
			<th>XP</th>
			<th>Gils</th>
			<th>Départ</th>
		</tr>
		<?php foreach ($races as $race): ?>
		<tr class="race">
			<td class="name"><?php echo html::anchor('races/' . $race->id, $race->circuit->name()) ?></td>
			<td class="length"><?php echo $race->circuit->length ?></td>
			<td class="pl"><?php echo $race->circuit->pl ?></td>
			<td class="xp"><?php echo $race->circuit->xp ?></td>
			<td class="gils"><?php echo $race->circuit->gils ?></td>
			<td class="nbr_chocobos"><?php echo count($race->chocobos) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	
</div>

<div style="float:right;">
	<?= html::anchor('races', Kohana::lang('race.refresh'), array('class'=>"button")) ?>
</div>

<div class="clearBoth"></div>

<h2>Historique des courses</h2>

<table class="results">
	<tr>
		<th>Nom</th>
		<th>Distance</th>
		<th>PL</th>
		<th>XP</th>
		<th>Gils</th>
		<th>Arrivée</th>
		<th>Date</th>
		<th>Options</th>
	</tr>
	<?php foreach ($results as $result): 
		$not_seen = ( ! $result->seen) ? ' not_seen': '';
		?>
		<tr class="result<?php echo $not_seen ?>" id="result<?php echo $result->race->id ?>">
			<td class="name"><?php echo html::anchor('races/' . $result->race->id, $result->race->circuit->name()) ?></td>
			<td class="length"><?php echo $result->race->circuit->length ?></td>
			<td class="pl"><?php echo $result->race->circuit->pl ?></td>
			<td class="xp"><?php echo $result->race->circuit->xp ?></td>
			<td class="gils"><?php echo $result->race->circuit->gils ?></td>
			<td class="nbr_chocobos"><?php echo count($result->race->results) ?></td>
			<td class="date">
				<?php 
				$tl = gen::time_left($result->race->start);
				echo ' <b>' . $tl['short'] . '</b>';
				?>
			</td>
			<td class="options">
				<?php
					if ($result->seen)
					{
						echo html::anchor('#', html::image('images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), array('class' => 'delete_result', 'id'=>'race' . $result->race->id));
					}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

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
