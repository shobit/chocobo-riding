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
	
	.label {width: 70%; float: left; font-variant: small-caps;}
	.value {text-align: right;}
	.p-wrapper {border-top: 1px solid #bbb;}
	.progress {height: 3px; margin-bottom: 10px;}
	.p-green {background-color: #090;}
	.p-yellow {background-color: #900;}
	.p-red {background-color: #900;}
	
	span.attributes {font-weight: normal; font-size: 10px;}
	
</style>

<?php
	//echo html::script('js/fusion.js');
	//echo html::script('js/chocobo_view.js');
	
	$mine = ($chocobo->user->id == $user->id);
?>

<h1><?=  Kohana::lang('chocobo.view.title') ?></h1>
<div id="prelude"><?=  Kohana::lang('chocobo.view.prelude') ?></div>

<p><?= html::anchor('rankings/chocobos', 'Liste des chocobos') ?> » 
	<b><?php 
		if ($chocobo->status == 2)
			echo html::image('images/icons/birthday.png');
		else	
			echo html::image('images/chocobos/'.$chocobo->display_colour('code').'/generic.gif').' ';
		echo $chocobo->name.' '.html::image('images/icons/'.$chocobo->display_gender('code').'.png');
		if ($chocobo->classe <= 6) echo ' '.html::image('images/classes/'.$chocobo->classe.'.gif'); 
		?>
	</b>
</p>

<?php
//////////////////
///// INFORMATIONS
//////////////////
?>
<div class="leftPart">

	<div class="line">
		<div class="label">côte</div>
		<div class="value"><?php echo $chocobo->display_fame() ?></div>
		<div class="p-wrapper">
			<?php echo progress::display(1 - $chocobo->fame + 0.01, 1, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">vitesse max</div>
		<div class="value"><?php echo $chocobo->max_speed ?> km/h</div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->max_speed, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">prix</div>
		<div class="value"><?php echo $chocobo->get_price() ?> gils</div>
		<div class="p-wrapper">
			<?php echo progress::display(0, 1, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">naissance</div>
		<div class="value"><?php $tl = gen::time_left($chocobo->birthday); echo $tl['short']; ?></div>
		<div class="p-wrapper">
			<?php echo progress::display(0, 1, 199) ?>
		</div>
	</div>
	
	
	

	<table class="informations">
	
		<?php if (!$mine) { ?>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.user') ?></td>
			<td class="value">
			<?php echo html::anchor('user/view/'.$chocobo->user->username, $chocobo->user->username) ?>
			</td>
		</tr><?php
		}
		if ($chocobo->father >0) { ?>
			<tr>
				<td class="label"><?= Kohana::lang('chocobo.father') ?></td>
				<td class="value">
				<?php 
					$father = ORM::factory('chocobo')->find($chocobo->father);
					echo html::image('images/chocobos/'.$father->display_colour('code').'/generic.gif').' ';
					echo html::anchor('chocobo/view/'.$father->name, $father->name);
					echo ' '.html::image('images/icons/'.$father->display_gender('code').'.png'); 
				?>
				</td>
			</tr>
			<? }
			if ($chocobo->mother >0) { ?>
			<tr>
				<td class="label"><?= Kohana::lang('chocobo.mother') ?></td>
				<td class="value">
				<?php 
					$mother = ORM::factory('chocobo')->find($chocobo->mother);
					echo html::image('images/chocobos/'.$mother->display_colour('code').'/generic.gif').' ';
					echo html::anchor('chocobo/view/'.$mother->name, $mother->name);
					echo ' '.html::image('images/icons/'.$mother->display_gender('code').'.png'); 
				?>
				</td>
			</tr>
		<?php } ?>

	</table>

</div>

<div class="leftPart">

	<div class="line">
		<div class="label">niveau</div>
		<div class="value"><?php echo $chocobo->level ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->level, $chocobo->lvl_limit, 199) ?>
		</div>
	</div>

	<div class="line">
		<div class="label">xp</div>
		<div class="value"><?php echo $chocobo->xp ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->xp, 100, 199) ?>
		</div>
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
		<div class="label">pl</div>
		<div class="value"><?php echo $chocobo->pl ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->pl, $chocobo->attr('pl_limit'), 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">vitesse</div>
		<div class="value"><?php echo $chocobo->speed ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->speed, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">endurance</div>
		<div class="value"><?php echo $chocobo->endur ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->endur, 175, 199) ?>
		</div>
	</div>
	
	<div class="line">
		<div class="label">intelligence</div>
		<div class="value"><?php echo $chocobo->endur ?></div>
		<div class="p-wrapper">
			<?php echo progress::display($chocobo->intel, 175, 199) ?>
		</div>
	</div>
	
	<?php if ($mine and $chocobo->points >0) { ?>
	<div id="points">PA à dépenser : 
		<b><span><?= $chocobo->points ?></span></b></div><br />
	<?php } ?>
	
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

</div>

<div class="clearBoth"></div>
