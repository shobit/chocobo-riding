<h1><?php echo Kohana::lang('inventory.title'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('inventory.prelude'); ?></div>

<p><b>Nombre d'objets possédés : <?php echo $nbr_items ?>/<?= $user->get_items() ?> 
<small>(+<?php echo $nbr_equipped ?>)</small></b></p>

<div class="leftPart2">

	<?php if ($nbr_items >0 or $nbr_equipped >0) { ?>
	
	<table class="shop">
	
	<?php
	foreach ($vegetables as $vegetable)
	{
		?><tr class="item">
			<td class="icon"><?php echo html::image('images/items/vegetables/vegetable'.$vegetable->name.'.gif') ?></td>
			<td class="name"><?php echo $vegetable->vignette() ?></td>
			<td class="price"><?php echo floor($vegetable->price /2) ?> Gils</td>
			<td class="form">
				<?php 
				echo html::anchor('vegetable/apply/'.$vegetable->id, 'utiliser', array("class"=>"shop")).' ';
				?>
			</td>
			<td class="form">
				<?php 
				echo html::anchor('vegetable/sale/'.$vegetable->id, 'vendre', array("class"=>"shop"));
				?>
			</td>
		</tr><?php
	}
	
	foreach ($nuts as $nut)
	{
		?><tr class="item">
			<td class="icon"><?php echo html::image('images/items/nuts/nut'.$nut->name.'.gif') ?></td>
			<td class="name"><?php echo $nut->vignette() ?></td>
			<td class="price"><?php echo floor($nut->price /2) ?> Gils</td>
			<td class="form"></td>
			<td class="form">
				<?php 
				echo html::anchor('nut/sale/'.$nut->id, 'vendre', array("class"=>"shop"));
				?>
			</td>
		</tr><?php
	}
	
	foreach ($equipment as $equip)
	{
		if ($equip->chocobo_id == NULL or $equip->chocobo_id == $chocobo->id) 
		{
		?><tr class="item">
			<td class="icon"><?php echo html::image('images/items/equips/equip'.$equip->type.'.gif') ?></td>
			<td class="name"><?php echo $equip->vignette() ?></td>
			<td class="price"><?php echo $equip->price ?> Gils</td>
			<td class="form">
				<?php 
				if ($equip->chocobo_id == NULL)
					echo html::anchor('equipment/apply/'.$equip->id, 'équiper', array("class"=>"shop"));
				else
					echo html::anchor('equipment/desapply/'.$equip->id, 'déséquiper', array("class"=>"shop"));
				?>
			</td>
			<td class="form">
				<?php 
				echo html::anchor('equipment/sale/'.$equip->id, 'vendre', array("class"=>"shop"));
				?>
			</td>
		</tr><?php
		}
	}
	?>
	
	</table>
	
	<?php } else { ?>
		
		<p><i>Votre inventaire est vide !</i></p>

	<?php } ?>

</div>

<div class="leftPart">
	<center>
		<p><?php echo html::image('images/pages/inventory.gif'); ?></p><br />
		
	</center>
</div>


	