<h2>Nombre d'objets possédés : <?php echo $nbr_items ?>/<?= $user->get_items() ?> (+<?php echo $nbr_equipped ?>)</h2>

<?php 
$gils_png = html::image('images/icons/gils.png', array('class' => 'icon4'));

if ($nbr_items >0 or $nbr_equipped >0) { 
?>

	<table class="table1">
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len150">Prix</th>
			<th class="len100"></th>
			<th class="len100"></th>
		</tr>

	<?php
	foreach ($vegetables as $vegetable)
	{
		?><tr>
			<td><?php echo $vegetable->vignette() ?></td>
			<td><?php echo floor($vegetable->price /2) . $gils_png ?></td>
			<td>
				<?php 
				echo html::anchor('vegetable/sale/'.$vegetable->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td>
				<?php 
				echo html::anchor('vegetable/apply/'.$vegetable->id, 'Utiliser', array("class"=>"button green")).' ';
				?>
			</td>
		</tr><?php
	}

	foreach ($nuts as $nut)
	{
		?><tr>
			<td><?php echo $nut->vignette() ?></td>
			<td><?php echo floor($nut->price /2) . $gils_png ?></td>
			<td>
				<?php 
				echo html::anchor('nut/sale/'.$nut->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td></td>
		</tr><?php
	}

	foreach ($equipment as $equip)
	{
		if ($equip->chocobo_id == NULL or $equip->chocobo_id == $chocobo->id) 
		{
		?><tr>
			<td><?php echo $equip->vignette() ?></td>
			<td><?php echo floor($equip->price /2) . $gils_png?></td>
			<td>
				<?php 
				echo html::anchor('equipment/sale/'.$equip->id, 'Vendre', array("class"=>"button red"));
				?>
			</td>
			<td>
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
	