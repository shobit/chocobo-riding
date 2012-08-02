<style>
.upgrade_shop {margin: 10px 0 30px 0;}
a.upgrade, a.upgrade:visited {background-color: #600; color: #FFF; padding: 4px; border-radius: 5px; font-size: 11px;}
</style>

<h2>Niveau de la boutique : <?php echo $user->get_shop() ?></h2>

<?php
$gils = $user->gils;
$gils_png = html::image('images/icons/gils.png', array('class' => 'icon4'));
?>

<table class="table1">
	<tr class="first">
		<th class="lenmax">Nom</th>
		<th class="len150">Prix</th>
		<th class="len100"></th>
	</tr>
		
	<?php foreach($vegetables as $vegetable): ?>
	<tr>
		<?php $price = $vegetable->price ?>
		<td><?php echo $vegetable->vignette() ?></td>
		<td><?php echo $price . $gils_png ?></td>
		<td> 
			<?php
			if ($gils >= $price) 
			{
				echo html::anchor('vegetable/buy/' . $vegetable->id, Kohana::lang('shop.buy'), array('class' => 'button green'));
			}
			?>
		</td>
	</tr>
	<?php endforeach; ?>

	<?php foreach($nuts as $nut): ?>
	<tr>
		<?php $price = $nut->price ?>
		<td><?php echo $nut->vignette() ?></td>
		<td><?php echo $price . $gils_png ?></td>
		<td> 
			<?php
			if ($gils >= $price) 
			{
				echo html::anchor('nut/buy/' . $nut->id, Kohana::lang('shop.buy'), array('class' => 'button green'));
			}
			?>
		</td>
	</tr>
	<?php endforeach; ?>

	<?php foreach($equipments as $equipment): ?>
	<tr>
		<?php $price = $equipment->price ?>
		<td><?php echo $equipment->vignette() ?></td>
		<td><?php echo $price . $gils_png ?></td>
		<td> 
			<?php
			if ($gils >= $price) 
			{
				echo html::anchor('equipment/buy/' . $equipment->id, Kohana::lang('shop.buy'), array('class' => 'button green'));
			}
			?>
		</td>
	</tr>
	<?php endforeach; ?>

	<?php foreach($chocobos as $chocobo): ?>
	<tr>
		<?php 
		$price = $chocobo->lvl_limit * 20;
		$chocobo->name = ($chocobo->gender == 2) ? 'Chocobo mâle': 'Chocobo femelle';
		?>
		<td><?php echo $chocobo->vignette() ?></td>
		<td><?php echo $price . $gils_png ?></td>
		<td> 
			<?php
			if ($gils >= $price) 
			{
				echo html::anchor('chocobo/buy/' . $chocobo->id, Kohana::lang('shop.buy'), array('class' => 'button green'));
			}
			?>
		</td>
	</tr>
	<?php endforeach; ?>

	<tr>
		<?php $cost = $user->get_boxes_cost() ?>
		<td><?php echo vignette::display('Box +', "Ajoute 1 box à l'écurie") ?></td>
		<td>
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
		<td> 
			<?php
			if ($gils >= $cost and $user->boxes < 5) 
			{
				echo html::anchor('user/upgrade_boxes', Kohana::lang('shop.buy'), array('id' => 'upgrade_boxes', 'class' => 'button green'));
			}
			?>
		</td>
	</tr>

	<tr>
		<?php $cost = $user->get_inventory_cost() ?>
		<td><?php echo vignette::display('Inventaire +', "Ajoute 2 places dans l'inventaire") ?></td>
		<td>
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
		<td> 
			<?php
			if ($gils >= $cost and $user->items < 5) 
			{
				echo html::anchor('user/upgrade_inventory', Kohana::lang('shop.buy'), array('id' => 'upgrade_inventory', 'class' => 'button green'));
			}
			?>
		</td>
	</tr>

	<tr>
		<?php $cost = $user->get_shop_cost() ?>
		<td><?php echo vignette::display('Boutique +', "Augmente de 1 le niveau de la boutique") ?></td>
		<td>
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
		<td> 
			<?php
			if ($gils >= $cost and $user->shop < 5) 
			{
				echo html::anchor('user/upgrade_shop', Kohana::lang('shop.buy'), array('id' => 'upgrade_shop', 'class' => 'button green'));
			}
			?>
		</td>
	</tr>

</table>

<script>
$(function(){
	$('.button').click(function(){
		$(this).parent().html('En cours..');
	});
});
</script>
