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

	<table class="informations">
	
		<?php if (!$mine) { ?>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.user') ?></td>
			<td class="value">
			<?= html::anchor('user/view/'.$chocobo->user->username, $chocobo->user->username); ?>
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
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.level') ?></td>
			<td class="value">
			<?= '<b>'.$chocobo->level.'</b> <small>/'.$chocobo->lvl_limit.'</small>'; ?>
			</td>
		</tr>
		<?php if ($chocobo->user->id == $user->id) { ?>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.experience') ?></td>
			<td class="value">
			<?= html::image('images/icons/exp.png').' '.$chocobo->xp.'%' ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.cel') ?></td>
			<td class="value">
			<?php
				echo html::image('images/theme/etoile.png').' ';
				echo $chocobo->display_fame();
			?>
			</td>
		</tr>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.max_speed') ?></td>
			<td class="value">
			<?php
				echo $chocobo->max_speed." km/h";
			?>
			</td>
		</tr>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.price') ?></td>
			<td class="value">
			<?php
				
				echo html::image('images/theme/gil.gif'). ' ';
				$content = $chocobo->get_price().' <small>gils</small>';
				echo $content;
			?>
			</td>
		</tr>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.birthday') ?></td>
			<td class="value">
			<?php $tl = gen::time_left($chocobo->birthday); echo $tl['short']; ?>
			</td>
		</tr>
		<tr>
			<td class="label"><?= Kohana::lang('chocobo.status') ?></td>
			<td class="value">
			<?= $chocobo->display_status() ?>
			</td>
		</tr>

	</table>

</div>

<div class="leftPart">

	<?php if ($mine and $chocobo->points >0) { ?>
	<div id="points">PA à dépenser : 
		<b><span><?= $chocobo->points ?></span></b></div><br />
	<?php } ?>

	<!-- VIT END INT -->
	<?php 
	$apts = array('speed', 'endur', 'intel');
	$caracs = array('pl', 'hp', 'mp');
	$colours = array('rouge', 'vert', 'bleu');
	
	for ($i=0; $i<=2; $i++) 
	{
		?>
		<div class="line_tab">
			
			<!-- APT -->
			<div class="line1 line_<?= $colours[$i] ?> bord_<?= $colours[$i] ?>">
				<div class="label">
					<?= html::image("images/icons/".$apts[$i].".png").' '.
						Kohana::lang('chocobo.'.$apts[$i]) ?></div>
				<div class="value">
					<?php 
					$apt = $chocobo->attr($apts[$i]);
					echo '<span id="'.$apts[$i].'">'.$apt."</span>";
					if ($mine and $chocobo->points >0)
					{
						echo ' <span class="speed_up points"><small>';
						echo html::anchor(
							'chocobo/aptitude_up/'.$apts[$i], 
							html::image('images/theme/plus.jpg')
						);
						echo '</small></span>';
					}
					?>
				</div>
			</div>
			
			<!-- APT_limit -->
			<div class="line line_<?= $colours[$i] ?>">
				<div class="label"><?= Kohana::lang('chocobo.'.$caracs[$i]) ?></div>
				<div class="value">
				<?php
					echo floor($chocobo->$caracs[$i]).' ';
					$apt = $chocobo->attr($caracs[$i].'_limit');
					echo '<small>/'.$apt.'</small>';
				?>
				</div>
			</div>
		</div>
		<?php 
	} 
	?>
	
	<!-- MORAL -->
	<div class="line_tab">
		<div class="line1 line_gris bord_gris">
			<div class="label"><?= html::image("images/icons/moral.png").' '.Kohana::lang('chocobo.moral') ?></div>
			<div class="value">
			<?php
				echo $chocobo->moral;
				echo '<span class="attributes">%</span>';
			?>
			</div>
		</div>
	</div>
	
	<!-- RAGE -->
	<div class="line_tab">
		<?php if ($mine) { ?>
		<div class="line1 line_gris bord_gris">
			<div class="label"><?= html::image("images/icons/rage.png").' '.Kohana::lang('chocobo.rage') ?></div>
			<div class="value">
			<?php
				echo $chocobo->rage;
				echo '<span class="attributes">/'.$chocobo->attr('rage_limit').'</span>';
			?>
			</div>
		</div>
		<?php } ?>
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

</div>

<div class="clearBoth"></div>
