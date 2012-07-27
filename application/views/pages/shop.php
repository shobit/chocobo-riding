<style>
.upgrade_shop {margin: 10px 0 30px 0;}
a.upgrade, a.upgrade:visited {background-color: #600; color: #FFF; padding: 4px; border-radius: 5px; font-size: 11px;}
</style>

<?php
$gils = $user->gils;
$gils_png = html::image('images/icons/gils.png', array('class' => 'icon4'));
?>

<h1><?php echo Kohana::lang('shop.title') . ' nv' . $user->get_shop() ?></h1>
<div id="prelude"><?php echo Kohana::lang('shop.prelude') ?></div>

<div class="leftPart2">
	
	<table class="shop">
		<thead>
				<tr>
					<th></th>
					<th>Nom</th>
					<th>Prix</th>
					<th></th>
				</tr>
		</thead>
		<tbody>
			<?php foreach($vegetables as $vegetable): ?>
			<tr class="item">
				<?php $price = $vegetable->price ?>
				<td class="icon"><?php echo html::image('images/items/vegetables/vegetable' . $vegetable->name . '.gif') ?></td>
				<td class="name"><?php echo $vegetable->vignette() ?></td>
				<td class="price"><?php echo $price . $gils_png ?></td>
				<td class="form"> 
					<?php
					if ($gils >= $price) 
					{
						echo html::anchor('vegetable/buy/' . $vegetable->id, Kohana::lang('shop.buy'), array('class' => 'button'));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>

			<?php foreach($nuts as $nut): ?>
			<tr class="item">
				<?php $price = $nut->price ?>
				<td class="icon"><?php echo html::image('images/items/nuts/nut' . $vegetable->name . '.gif') ?></td>
				<td class="name"><?php echo $nut->vignette() ?></td>
				<td class="price"><?php echo $price . $gils_png ?></td>
				<td class="form"> 
					<?php
					if ($gils >= $price) 
					{
						echo html::anchor('nut/buy/' . $nut->id, Kohana::lang('shop.buy'), array('class' => 'button'));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>

			<?php foreach($equipments as $equipment): ?>
			<tr class="item">
				<?php $price = $equipment->price ?>
				<td class="icon"><?php echo html::image('images/items/nuts/equipment' . $vegetable->name . '.gif') ?></td>
				<td class="name"><?php echo $equipment->vignette() ?></td>
				<td class="price"><?php echo $price . $gils_png ?></td>
				<td class="form"> 
					<?php
					if ($gils >= $price) 
					{
						echo html::anchor('equipment/buy/' . $equipment->id, Kohana::lang('shop.buy'), array('class' => 'button'));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>

			<?php foreach($chocobos as $chocobo): ?>
			<tr class="item">
				<?php 
				$price = $chocobo->lvl_limit * 20;
				$chocobo->name = ($chocobo->gender == 2) ? 'Chocobo mâle': 'Chocobo femelle';
				?>
				<td class="icon"><?php echo html::image('images/chocobos/yellow/generic.gif') ?></td>
				<td class="name"><?php echo $chocobo->vignette() ?></td>
				<td class="price"><?php echo $price . $gils_png ?></td>
				<td class="form"> 
					<?php
					if ($gils >= $price) 
					{
						echo html::anchor('chocobo/buy/' . $chocobo->id, Kohana::lang('shop.buy'), array('class' => 'button'));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>

			<tr class="item">
				<?php $cost = $user->get_boxes_cost() ?>
				<td class="icon"></td>
				<td class="name"><?php echo vignette::display('Box +', "Ajoute 1 box à l'écurie") ?></td>
				<td class="price">
					<?php 
					if ($user->boxes < 5)
					{
						echo $cost . $gils_png;
					}
					else
					{
						echo 'max.';
					}
					?>
				</td>
				<td class="form"> 
					<?php
					if ($gils >= $cost and $user->boxes < 5) 
					{
						echo html::anchor('user/upgrade_boxes', Kohana::lang('shop.buy'), array('id' => 'upgrade_boxes', 'class' => 'button'));
					}
					?>
				</td>
			</tr>

			<tr class="item">
				<?php $cost = $user->get_inventory_cost() ?>
				<td class="icon"></td>
				<td class="name"><?php echo vignette::display('Inventaire +', "Ajoute 2 places dans l'inventaire") ?></td>
				<td class="price">
					<?php 
					if ($user->items < 5)
					{
						echo $cost . $gils_png;
					}
					else
					{
						echo 'max.';
					}
					?>
				</td>
				<td class="form"> 
					<?php
					if ($gils >= $cost and $user->items < 5) 
					{
						echo html::anchor('user/upgrade_inventory', Kohana::lang('shop.buy'), array('id' => 'upgrade_inventory', 'class' => 'button'));
					}
					?>
				</td>
			</tr>

			<tr class="item">
				<?php $cost = $user->get_shop_cost() ?>
				<td class="icon"></td>
				<td class="name"><?php echo vignette::display('Boutique +', "Augmente de 1 le niveau de la boutique") ?></td>
				<td class="price">
					<?php 
					if ($user->shop < 5)
					{
						echo $cost . $gils_png;
					}
					else
					{
						echo 'max.';
					}
					?>
				</td>
				<td class="form"> 
					<?php
					if ($gils >= $cost and $user->shop < 5) 
					{
						echo html::anchor('user/upgrade_shop', Kohana::lang('shop.buy'), array('id' => 'upgrade_shop', 'class' => 'button'));
					}
					?>
				</td>
			</tr>

		</tbody>
	</table>

</div>

<div class="leftPart">
	<center>
	
		<?php echo html::image('images/pages/shop.jpg', array('class' => 'location')) ?>

		<p><?php echo Kohana::lang('shop.gils_remain') ?><br />
			<b><span id="gils"><?php echo $user->gils ?></span></b></p>
	
	</center>
</div>

<script>
$(function(){
	$('.button').click(function(){
		$(this).parent().html('En cours..');
	});
});
</script>
