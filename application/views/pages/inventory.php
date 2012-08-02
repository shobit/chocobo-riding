<h2>Nombre d'objets possédés : <?php echo $nbr_items ?>/<?= $user->get_items() ?> (+<?php echo $nbr_equipped ?>)</h2>

<?php 
$gils_png = html::image('images/icons/gils.png', array('class' => 'icon4'));

if ($nbr_items >0 or $nbr_equipped >0) { 
?>

	<table class="table1">
		<tr>
			<th>Nom</th>
			<th>Prix</th>
			<th></th>
			<th></th>
		</tr>

	<?php
	foreach ($vegetables as $vegetable)
	{
		?><tr class="tr1">
			<td class="lenmax"><?php echo $vegetable->vignette() ?></td>
			<td class="len150"><?php echo floor($vegetable->price /2) . $gils_png ?></td>
			<td class="len100">
				<?php 
				echo html::anchor('vegetable/sale/'.$vegetable->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td class="len100">
				<?php 
				echo html::anchor('vegetable/apply/'.$vegetable->id, 'Utiliser', array("class"=>"button green")).' ';
				?>
			</td>
		</tr><?php
	}

	foreach ($nuts as $nut)
	{
		?><tr class="tr1">
			<td class="lenmax"><?php echo $nut->vignette() ?></td>
			<td class="len150"><?php echo floor($nut->price /2) . $gils_png ?></td>
			<td class="len100">
				<?php 
				echo html::anchor('nut/sale/'.$nut->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td class="len100"></td>
		</tr><?php
	}

	foreach ($equipment as $equip)
	{
		if ($equip->chocobo_id == NULL or $equip->chocobo_id == $chocobo->id) 
		{
		?><tr class="tr1">
			<td class="lenmax"><?php echo $equip->vignette() ?></td>
			<td class="len150"><?php echo floor($equip->price /2) . $gils_png?></td>
			<td class="len100">
				<?php 
				echo html::anchor('equipment/sale/'.$equip->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td class="len100">
				<?php 
				if ($equip->chocobo_id == NULL)
					echo html::anchor('equipment/apply/'.$equip->id, 'Equiper', array("class"=>"button green"));
				else
					echo html::anchor('equipment/desapply/'.$equip->id, 'Déséquiper', array("class"=>"button green"));
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
	