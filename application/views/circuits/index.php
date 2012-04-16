<style>
	.races {width: 100%; border-collapse: collapse;}
	.races tr {border-bottom: 1px solid #ddd;height: 40px;}
	.races tr:hover {background-color: #eee; cursor: pointer;}
	.race-time {width: 55px; color: #bbb; font-size: 26px;}
	.race-location {width: 150px;}
	.race-length {width: 70px; text-align: center;}
	.race-bonus {}
	.races-chocobos {}
	
	.results {width: 100%; border-bottom: 1px solid #e4e4e4;}
	.result {height: 40px; border-top: 1px solid #e4e4e4; position: relative;}
	.not_seen {background-color: #fee;}
	
	.options {position: absolute; top: 14px; right: 5px; display: none; width: 250px; text-align: right; padding-right: 5px;}
	.options a, .options a:visited {text-decoration: none; color: #666; font-style: italic;}
	.options a:hover {text-decoration: underline;}
</style>

<h1><?php echo Kohana::lang('circuit.index.title') . ' ' . html::image("images/classes/$classe.gif"); ?></h1>
<div id="prelude"><?php echo Kohana::lang('circuit.index.prelude'); ?></div>

<div class="circuit">
	<div class="column2">
		<?= html::image('images/pages/circuits.jpg', array('class'=>'location')) ?>
	</div>

	<?php foreach ($circuits as $circuit): ?>
		<div>
			<?php 
			echo html::anchor('circuits/' . $circuit->id, $circuit->location->display_name());
			$nbr_chocobos = count($circuit->chocobos);
			if ($nbr_chocobos > 0) $nbr_chocobos = "<b>$nbr_chocobos</b>";
			?>
			 (<?php echo $nbr_chocobos; ?>)<br /><?php echo html::image('images/icons/hour2.png'); ?>
			<span style="display: inline;" id="<?php echo $circuit->id; ?>">--:--</span>
			
			<script language=JavaScript>
				decompte(
					'<?= $circuit->id  ?>', 
					'<?= ($circuit->start - time()) ?>', 
					'<?= Kohana::lang('circuit.index.finished') ?>',
					false
				);
			</script>
		</div>
	<?php endforeach; ?>
	
</div>

<div style="float:right;">
	<?= html::anchor('circuits', Kohana::lang('circuit.refresh'), array('class'=>"button")) ?>
</div>

<div class="clearBoth"></div>

<h2>Historique des courses</h2>

<div class="results">
	<?php foreach ($results as $result): 
		$not_seen = ( ! $result->seen) ? ' not_seen': '';
		?>
		<div class="result<?php echo $not_seen ?>" id="result<?php echo $result->circuit->id ?>">
			<div class="options">
				<?php 
					echo html::anchor('#', 
					html::image('images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), 
						array('class' => 'delete_result', 'id'=>'race' . $result->circuit->id));
			
				?>
			</div>
			<div>
				<?php 
				echo $result->circuit->id . '. ';
				echo html::anchor('circuits/' . $result->circuit->id, $result->circuit->location->display_name()); 
				$tl = gen::time_left($result->circuit->start);
				echo ' <b>' . $tl['short'] . '</b>';
				?>
			</div>
		</div>
		<div class="clearLeft"></div>
	<?php endforeach; ?>
</div>

<script>

$(function(){
	
	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	$('.result').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});
	
	$('.delete_result')
		.click(function(){
			var circuit_id = $(this).attr('id').substring(4);
			$(this).parent().hide();
			$.post(baseUrl + 'circuits/delete', {'id': circuit_id}, function(data){
				if (data.success) {
					$('#result' + circuit_id).slideUp();
				}
			});
			return false;
		});
});

</script>
