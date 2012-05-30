<?php
$gils = $user->gils;
$boxes = $user->boxes;
?>

<h1><?= Kohana::lang('shop.title') ?></h1>
<div id="prelude"><?= Kohana::lang('shop.prelude') ?></div>

<p><b>Niveau de la boutique : <?php echo ($user->shop +1); ?></b></p>

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
				<td class="price"><?php echo $price ?> Gils</td>
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

			<?php foreach($chocobos as $chocobo): ?>
			<tr class="item">
				<?php 
				$price = $chocobo->lvl_limit * 20;
				$chocobo->name = ($chocobo->gender == 1) ? 'Chocobo mâle': 'Chocobo femelle';
				?>
				<td class="icon"><?php echo html::image('images/chocobos/yellow/generic.gif') ?></td>
				<td class="name"><?php echo $chocobo->vignette() ?></td>
				<td class="price"><?php echo $price ?> Gils</td>
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
