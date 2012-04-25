<style>
	div.line_tab {
		margin-bottom: 20px;
	}
	
	div.line {
		font-size: 1.0em;
		padding: 6px 0 0 6px;
	}
	
	div.line1 {
		font-size: 1.1em;
		letter-spacing: 1px;
		font-weight: bold;
	}
	
	div.line_rouge, span.line_rouge {color: #800000;}
	div.line_vert, span.line_vert {color: #184500;}
	div.line_bleu, span.line_bleu {color: #191970;}
	div.line_gris, span.line_gris {color: #555;}
	div.line_noir, span.line_noir {color: #000;}
	
	div.bord_rouge {border-bottom: 1px dotted #800000;}
	div.bord_vert {border-bottom: 1px dotted #184500;}
	div.bord_bleu {border-bottom: 1px dotted #191970;}
	div.bord_gris {border-bottom: 1px dotted #555;}
	div.bord_noir {border-bottom: 1px dotted #000;}
	
	.label {
		width: 55%; 
		float: left; 
		font-variant: small-caps;
		font-size: 16px;
		font-family: Arial;
		letter-spacing: 1px;
		color: #999;
	}
	.value {
		text-align: right;
		color: #666;
	}
	.p-wrapper {border-top: 1px solid #bbb; margin-bottom: 10px;}
	.p-wrapper2 {border-top: 1px solid #bbb; margin-bottom: 13px;}
	.p-wrapper3 {border-top: 1px solid #bbb; margin-bottom: 40px;}
	.progress {height: 3px;}
	.p-green {background-color: #090;}
	.p-yellow {background-color: #900;}
	.p-red {background-color: #900;}
	.info {display:none;}
	
	span.attributes {font-weight: normal; font-size: 10px;}
	
	.raise {margin-left: 3px; margin-bottom: -4px;}
	
</style>

<?php
	//echo html::script('js/fusion.js');
	//echo html::script('js/chocobo_view.js');
	
	$mine = ($chocobo->user->id == $user->id);
?>

<h1><?=  Kohana::lang('chocobo.view.title') ?></h1>
<div id="prelude"><?=  Kohana::lang('chocobo.view.prelude') ?></div>

<?php
//////////////////
///// INFORMATIONS
//////////////////
?>
<div class="leftPart">

	<div class="line">
		<div class="label">nom</div>
		<div class="value"><?php echo $chocobo->name ?></div>
		<div class="p-wrapper2"></div>
		<div class="info">Nom du chocobo.</div>
	</div>
	
	<div class="line">
		<div class="label">couleur</div>
		<div class="value"><?php echo $chocobo->display_colour('zone') ?></div>
		<div class="p-wrapper2"></div>
		<div class="info">
			La couleur du chocobo privilégie l'évolutions de 
			certaines caractéristiques sur d'autres.
		</div>
	</div>
	
	<div class="line">
		<div class="label">job</div>
		<div class="value">chocobo</div>
		<div class="p-wrapper2"></div>
		<div class="info">
			Le job procure des compétences de courses à votre chocobo.
		</div>
	</div>
	
	<div class="line">
		<div class="label">classe</div>
		<div class="value"><?php echo $chocobo->display_classe() ?></div>
		<div class="p-wrapper2"></div>
		<div class="info">
			Votre chocobo monte de classe à certains niveaux. 
			Il ne peut courir qu'à des courses de même classe.
		</div>
	</div>
	
	<div class="line">
		<div class="label">niveau</div>
		<div class="value"><?php echo $chocobo->level ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->level, $chocobo->lvl_limit, 199) ?>
		</div>
		<div class="info">
			Le niveau du chocobo est le résultat de l'expérience acquise dans les courses.<br />
			Niveau maximal : <b><?php echo $chocobo->lvl_limit ?></b>
		</div>
	</div>

	<div class="line">
		<div class="label">exp</div>
		<div class="value"><?php echo $chocobo->xp ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->xp, 100, 199) ?>
		</div>
		<div class="info">
			Votre chocobo gagne de l'expérience en fin 
			de course et peut ainsi monter de niveau.
		</div>
	</div>
	
	<div class="line">
		<div class="label">côte</div>
		<div class="value"><?php echo $chocobo->display_fame() ?></div>
		<div class="p-wrapper">
			<?php echo progress::display(1 - $chocobo->fame + 0.01, 1, 199) ?>
		</div>
		<div class="info">
			Plus la côte du chocobo est faible, plus le chocobo est cher à la revente.
		</div>
	</div>
	
	<div class="line">
		<div class="label">vitesse max</div>
		<div class="value"><?php echo $chocobo->max_speed ?> km/h</div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->max_speed, 175, 199) ?>
		</div>
		<div class="info">
			La vitesse maximale que votre chocobo a atteint dans les courses.
		</div>
	</div>
	
	<div class="line">
		<div class="label">prix</div>
		<div class="value"><?php echo $chocobo->get_price() ?> gils</div>
		<div class="p-wrapper">
			<?php echo progress::display(0, 1, 199) ?>
		</div>
		<div class="info">
			Le prix auquel vous pouvez négocier votre chocobo si vous le mettez en vente.
		</div>
	</div>
	
	<div class="line">
		<div class="label">naissance</div>
		<div class="value"><?php echo date::display($chocobo->birthday); ?></div>
		<div class="p-wrapper2"></div>
		<div class="info">
			Le jour de naissance de votre chocobo.
		</div>
	</div>
	
</div>

<div class="leftPart">

	<div class="line">
		<div class="label">vitesse</div>
		<div class="value">
			<?php
			echo $chocobo->speed;
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
			<?php echo progress::display($chocobo->speed, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">endurance</div>
		<div class="value">
			<?php
			echo $chocobo->endur;
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
			<?php echo progress::display($chocobo->endur, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">intelligence</div>
		<div class="value">
			<?php
			echo $chocobo->intel; 
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
			<?php echo progress::display($chocobo->intel, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">pa</div>
		<div class="value"><?php echo $chocobo->points ?></div>
		<div class="p-wrapper3"></div>
	</div>
	
	<div class="line">
		<div class="label">hp</div>
		<div class="value"><?php echo $chocobo->hp ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->hp, $chocobo->attr('hp_limit'), 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">mp</div>
		<div class="value"><?php echo $chocobo->mp ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->mp, $chocobo->attr('mp_limit'), 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">rage</div>
		<div class="value"><?php echo $chocobo->rage ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->rage, $chocobo->attr('rage_limit'), 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">résistance</div>
		<div class="value">0</div>
		<div class="p-wrapper"></div>
	</div>
	
	<div class="line">
		<div class="label">pl</div>
		<div class="value"><?php echo $chocobo->pl ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->pl, $chocobo->attr('pl_limit'), 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">pc</div>
		<div class="value"><?php echo $chocobo->attr('pc_limit') ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->attr('pc_limit'), $chocobo->attr('pc_limit'), 199) ?>
		</div>
	</div>
	
</div>
	
<?php
///////////
///// IMAGE
///////////
?>
<div class="leftPart">
	
	<!-- IMAGE -->
	<center>
		<?php 
		if ($chocobo->status == 2)	
			echo html::image('images/chocobos/'.$chocobo->display_colour('code').
				'/baby/big.png');
		else	
			echo html::image('images/chocobos/'.$chocobo->display_colour('code').
				'/'.$chocobo->display_job('code').'/big.png'); 
		
		if ($chocobo->user->id == $user->id and count($chocobo->user->chocobos) > 1)
		{
			$texte = "Are you sure ?";
			echo '<br />'.html::anchor(
				'chocobo/sale/'.$chocobo->name, 
				html::image('images/buttons/sale.gif'), 
				array('onClick'=>'javascript: return confirm(\''.$texte.'\');')
			);
		}	
		?>	
	</center>
	
	<div id="i-wrapper"></div>

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