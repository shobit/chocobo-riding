<h2>Nombre d'objets possédés : <?php echo $nbr_items ?>/<?php echo $user->get_items() ?> (+<?php echo $nbr_equipped ?>)</h2>

<?php 
$gils_png = HTML::image('media/images/icons/gils.png', array('class' => 'icon4'));

if ($nbr_items >0 or $nbr_equipped >0): 
?>

	<table class="table1">
		<tr class="first">
			<th class="lenmax">Nom</th>
			<th class="len150">Prix</th>
			<th class="len100"></th>
			<th class="len100"></th>
		</tr>

	<?php
	foreach ($user->vegetables->find_all() as $vegetable)
	{
		?><tr>
			<td><?php echo $vegetable->vignette() ?></td>
			<td><?php echo floor($vegetable->price /2).$gils_png ?></td>
			<td>
				<?php 
				echo HTML::anchor('vegetable/sale/'.$vegetable->id, __('Vendre'), array("class"=>"button red"));
				?>
			</td>
			<td>
				<?php 
				echo HTML::anchor('vegetable/apply/'.$vegetable->id, __('Utiliser'), array("class"=>"button green")).' ';
				?>
			</td>
		</tr><?php
	}

	foreach ($user->nuts->find_all() as $nut)
	{
		?><tr>
			<td><?php echo $nut->vignette() ?></td>
			<td><?php echo floor($nut->price /2).$gils_png ?></td>
			<td>
				<?php 
				echo HTML::anchor('nut/sale/'.$nut->id, __('Vendre'), array("class"=>"button red"));
				?>
			</td>
			<td></td>
		</tr><?php
	}

	foreach ($user->equipment->find_all() as $equip)
	{
		if ($equip->chocobo_id == 0 or $equip->chocobo_id == $chocobo->id) 
		{
		?><tr>
			<td><?php echo $equip->vignette() ?></td>
			<td><?php echo floor($equip->price /2).$gils_png?></td>
			<td>
				<?php 
				echo HTML::anchor('equipment/sale/'.$equip->id, __('Vendre'), array("class"=>"button red"));
				?>
			</td>
			<td>
				<?php 
				if ($equip->chocobo_id == 0)
					echo HTML::anchor('equipment/apply/'.$equip->id, __('Equiper'), array("class"=>"button green"));
				else
					echo HTML::anchor('equipment/desapply/'.$equip->id, __('Déséquiper'), array("class"=>"button green"));
				?>
			</td>
		</tr><?php
		}
	}
	?>

	</table>

<?php else: ?>
	
	<p><i>Votre inventaire est vide !</i></p>

<?php endif; ?>
	