<?php
	//echo html::script('js/shop.js');
?>

<h1><?= Kohana::lang('shop.title') ?></h1>
<div id="prelude"><?= Kohana::lang('shop.prelude') ?></div>

<div class="leftPart2">
	
	<table class="shop">
	
		<?php
		$gils 			= $user->gils;
		$boxes 			= $user->boxes;
		$items			= $user->items;
		$nbr_chocobos 	= count($user->chocobos);
		?>
	
		<tr class="item">
			<?php $achat = 50; ?>
			<td class="icon"><?= html::image('images/items/vegetables/vegetable1.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.vegetable1.name'), 
				Kohana::lang('shop.vegetable1.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/vegetable1", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $achat = 60; ?>
			<td class="icon"><?= html::image('images/items/vegetables/vegetable2.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.vegetable2.name'), 
				Kohana::lang('shop.vegetable2.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/vegetable2", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $achat = 300; ?>
			<td class="icon"><?= html::image('images/items/nuts/nut1.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.nut.name'), 
				Kohana::lang('shop.nut.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/nut", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $achat = 150; ?>
			<td class="icon"><?= html::image('images/items/equips/equip1.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.equipment1.name'), 
				Kohana::lang('shop.equipment1.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/equipment1", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $achat = 150; ?>
			<td class="icon"><?= html::image('images/items/equips/equip2.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.equipment2.name'), 
				Kohana::lang('shop.equipment2.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/equipment2", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $achat = 150; ?>
			<td class="icon"><?= html::image('images/items/equips/equip3.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.equipment3.name'), 
				Kohana::lang('shop.equipment3.bubble')) 
			?></td>
			<td class="price"><?= $achat ?> Gils</td>
			<td class="form">
				<?php
					if ($gils >= $achat) echo html::anchor("shop/buy/equipment3", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
	
		<tr class="item">
			<?php $price = $PRICE_CHOCOBO_M + ($nbr_chocobos-1)*100; ?>
			<td class="icon"><?= html::image('images/chocobos/yellow/generic.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.chocobo_m.name'), 
				Kohana::lang('shop.chocobo_m.bubble')) 
			?></td>
			<td class="price"><?= $price ?> Gils</td>
			<td class="form">
				<?php
					if ($nbr_chocobos < $boxes and $gils >= $price) 
						echo html::anchor("shop/buy/chocobo_m", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
			
		<tr class="item">
			<?php $price = $PRICE_CHOCOBO_F + ($nbr_chocobos-1)*100; ?>
			<td class="icon"><?= html::image('images/chocobos/yellow/generic.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.chocobo_f.name'), 
				Kohana::lang('shop.chocobo_f.bubble')) 
			?></td>
			<td class="price"><?= $price ?> Gils</td>
			<td class="form">
				<?php
					if ($nbr_chocobos < $boxes and $gils >= $price) 
						echo html::anchor("shop/buy/chocobo_f", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $price = $PRICE_LICENCE + ($boxes-2)*300; ?>
			<td class="icon"><?= html::image('images/icons/skills.png') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.license.name'), 
				Kohana::lang('shop.license.bubble')) 
			?></td>
			<td class="price"><?= $price ?> Gils</td>
			<td class="form"> 
				<?php
					if ($gils >= $price and $boxes < $user->BOXES_LIMIT) 
						echo html::anchor("shop/buy/licence", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
		
		<tr class="item">
			<?php $price = $PRICE_BIG_BAG + ($items-10)*200; ?>
			<td class="icon"><?= html::image('images/menu/sac.gif') ?></td>
			<td class="name"><?= gen::vignette(
				Kohana::lang('shop.big_bag.name'), 
				Kohana::lang('shop.big_bag.bubble')) 
			?></td>
			<td class="price"><?= $price ?> Gils</td>
			<td class="form"> 
				<?php
					if ($gils >= $price and $items < $user->ITEMS_LIMIT) 
						echo html::anchor("shop/buy/big_bag", Kohana::lang('shop.buy'), array("class"=>"button"));
				?>
			</td>
		</tr>
	
	</table>

</div>

<div class="leftPart">
	<center>
	
		<?= html::image('images/pages/shop.jpg', array('class'=>'location')) ?>

		<p><?= Kohana::lang('shop.gils_remain') ?><br />
			<b><span id="gils"><?= $user->gils ?></span></b></p>
	
	</center>
</div>
