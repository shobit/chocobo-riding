<style>
.progress {height: 3px;}
.p-green {background-color: #090;}
.p-yellow {background-color: #900;}
.p-red {background-color: #900;}
.p-grey {background-color: #999;}
</style>

<?php $mine = ($chocobo->user->id == $user->id) ?>

<?php
//////////////////
///// INFORMATIONS
//////////////////
?>

<h2>Informations</h2>

<table class="table1">

	<tr>
		<th>Champ</th>
		<th>Valeur</th>
		<th></th>
	</tr>

	<tr class="tr1">
		<td class="len150">Nom</td>
		<td class="lenmax"><?php echo $chocobo->name ?></td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Couleur</td>
		<td class="lenmax"><?php echo ucfirst($chocobo->display_colour('zone')) ?></td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Job</td>
		<td class="lenmax">Chocobo</td>
		<td class="len100"></td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">classe</td>
		<td class="lenmax"><?php echo $chocobo->display_classe() ?></td>
		<td class="len100">
			<?php echo progress::display($chocobo->classe, 5, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Niveau</td>
		<td class="lenmax"><?php echo $chocobo->level . ' /' . $chocobo->lvl_limit ?></td>
		<td class="p-wrapper len100">
			<?php echo progress::display($chocobo->level, $chocobo->lvl_limit, 100) ?>
		</td>
	</tr>

	<tr class="tr1">
		<td class="len150">Expérience</td>
		<td class="lenmax"><?php echo $chocobo->xp ?>xp /100</td>
		<td class="len100 p-wrapper4">
			<?php echo progress::display($chocobo->xp, 100, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Côte</td>
		<td class="lenmax"><?php echo $chocobo->display_fame() ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display(1 - $chocobo->fame + 0.01, 1, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Vitesse max</td>
		<td class="lenmax"><?php echo $chocobo->max_speed ?> km/h</td>
		<td class="len100 p-wrapper">
			<?php echo progress::display($chocobo->max_speed, 175, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">Prix</td>
		<td class="lenmax"><?php echo $chocobo->get_price() . html::image('images/icons/gils.png', array('class' => 'icon4')) ?></td>
		<td class="len100 p-wrapper">
			<?php echo progress::display(0, 1, 100) ?>
		</td>
	</tr>
	
	<tr class="tr1">
		<td class="len150">naissance</td>
		<td class="lenmax"><?php echo date::display($chocobo->birthday) ?></td>
		<td class="len100 p-wrapper2"></td>
	</tr>
	
</table>

<div class="leftPart">

	<div class="line">
		<div class="image"><?php echo html::image('images/icons/speed.png') ?></div>
		<div class="l-wrapper">
			<div class="label">vitesse</div>
			<div class="value">
				<?php
				echo $chocobo->attr('speed');
				if ($mine and $chocobo->points > 0)
				{
					echo html::anchor(
						'chocobo/aptitude_up/speed', 
						html::image('images/theme/plus.jpg', array('class' => 'raise'))
					);
				}
				?>
			</div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->attr('speed'), 175, 180) ?>
			</div>
			<div class="info">
				La vitesse influe dans les calculs de la vitesse moyenne en course. 
				La longueur des courses entre également en compte.<br />
				600m : <?php echo ceil($chocobo->attr('speed') / 2) ?> km/h<br />
				1200m : <?php echo ceil($chocobo->attr('speed') / 3) ?> km/h<br />
				1800m : <?php echo ceil($chocobo->attr('speed') / 4) ?> km/h<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/endur.png') ?></div>
		<div class="l-wrapper">
			<div class="label">endurance</div>
			<div class="value">
				<?php
				echo $chocobo->attr('endur');
				if ($mine and $chocobo->points > 0)
				{
					echo html::anchor(
						'chocobo/aptitude_up/endur', 
						html::image('images/theme/plus.jpg', array('class' => 'raise'))
					);
				}
				?>
			</div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->attr('endur'), 175, 180) ?>
			</div>
			<div class="info">
				L'endurance intervient dans le calcul des HP et des PL du chocobo.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/intel.png') ?></div>
		<div class="l-wrapper">
			<div class="label">intelligence</div>
			<div class="value">
				<?php
				echo $chocobo->attr('intel'); 
				if ($mine and $chocobo->points > 0)
				{
					echo html::anchor(
						'chocobo/aptitude_up/intel', 
						html::image('images/theme/plus.jpg', array('class' => 'raise'))
					);
				}
				?>
			</div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->attr('intel'), 175, 180) ?>
			</div>
			<div class="info">
				L'intelligence intervient dans le calcul des MP et des PC du chocobo.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/pa.png') ?></div>
		<div class="l-wrapper">
			<div class="label">pa</div>
			<div class="value"><?php echo $chocobo->points ?></div>
			<div class="p-wrapper3"></div>
			<div class="info">
				Les PA peuvent être dépensés pour améliorer les aptitudes du chocobo.
				Le chocobo gagne 2 PA à chaque niveau gagné.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/pl.png') ?></div>
		<div class="l-wrapper">
			<div class="label">pl</div>
			<div class="value"><?php echo $chocobo->pl ?></div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->pl, $chocobo->attr('pl_limit'), 180) ?>
			</div>
			<div class="info">
				Les PL sont nécessaires pour inscrire le chocobo à une course. 
				Avec le temps, le chocobo regénère ses PL petit à petit.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
		
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/hp.gif') ?></div>
		<div class="l-wrapper">
			<div class="label">hp</div>
			<div class="value"><?php echo $chocobo->hp ?></div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->hp, $chocobo->attr('hp_limit'), 180) ?>
			</div>
			<div class="info">
				Les HP du chocobo symbolisent ses points de vie. Lorsque le chocobo est blessé 
				en course, ils diminuent.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/mp.gif') ?></div>
		<div class="l-wrapper">
			<div class="label">mp</div>
			<div class="value"><?php echo $chocobo->mp ?></div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->mp, $chocobo->attr('mp_limit'), 180) ?>
			</div>
			<div class="info">
				Les MP du chocobo symbolisent ses points de mana. 
				Le chocobo consomme ses MP en courses pour utiliser ses compétences.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/pc.png') ?></div>
		<div class="l-wrapper">
			<div class="label">pc</div>
			<div class="value"><?php echo $chocobo->attr('pc_limit') ?></div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->attr('pc_limit'), $chocobo->attr('pc_limit'), 180) ?>
			</div>
			<div class="info">
				Les PC du chocobo symbolisent ses points de compétences. 
				Le chocobo consomme ses PC en activant ses compétences de job.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/rage.png') ?></div>
		<div class="l-wrapper">
			<div class="label">rage</div>
			<div class="value"><?php echo $chocobo->rage ?></div>
			<div class="p-wrapper">
				<?php echo progress::display($chocobo->rage, $chocobo->attr('rage_limit'), 180) ?>
			</div>
			<div class="info">
				Un chocobo gagne des points de rage à la fin de chaque course en fonction de sa place et du nombre de chocobos. 
				Lorsque la rage est à son maximum, elle se manifeste dès la prochaine course en lui procurant un bonus de vitesse moyenne.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="line">
		<div class="image"><?php echo html::image('images/icons/resistance.gif') ?></div>
		<div class="l-wrapper">
			<div class="label">résistance</div>
			<div class="value"><?php echo $chocobo->attr('resistance') ?></div>
			<div class="p-wrapper"></div>
			<div class="info">
				Les équipements du chocobo lui procurent une protection contre les attaques ennemies.<br />
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	
</div>
	
<?php
///////////
///// IMAGE
///////////
?>
<div class="leftPart">
	
	<!-- IMAGE -->
	<div class="chocobo_image">
		<?php 
		echo html::image('images/chocobos/'.$chocobo->display_colour('code').
			'/'.$chocobo->display_job('code').'/big.png'); 
		?>	
	</div>
	
	<div class="actions">
		<?php
		if ($chocobo->user->id == $user->id and count($chocobo->user->chocobos) > 1)
		{
			$texte = "Are you sure ?";
			echo html::anchor('chocobo/sale/' . $chocobo->id, 'Vendre', array('class' => 'button', 'onclick' => "return confirm('$texte');"));
		}
		?>
	</div>
	
	<div id="i-wrapper" style="display:none;"></div>

</div>

<div class="clearBoth"></div>

<script>
$(function(){

	$('.line').hover(
		function(){
			var text = $(this).find('.info').html();
			$('#i-wrapper').html(text).show();
		}, 
		function(){
			$('#i-wrapper').hide();
		}
	);

});
</script>