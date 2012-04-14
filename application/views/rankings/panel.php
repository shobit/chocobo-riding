<div class="circuit">
	<div class="column2">
		<?= html::image('images/pages/circuits.jpg', array('class'=>'location')) ?>
	</div>
	
	<?php
		foreach ($types as $i => $type)
		{
		?>
			<div class="column2">
				<div class="title"><?= $labels['types'][$i] ?></div>
				<table class="circuitInside">
				<?php
					foreach ($orders[$type] as $j => $order)
					{
					?>
						<tr>
							<td class="icon"></td>
							<td>
							<?php 	
								if ($code == $type.'.'.$order) echo "<b>";
								echo html::anchor('rankings/'.$type.'/'.$order, $labels[$type][$j]); 
								if ($code == $type.'.'.$order) echo "</b>";
							?>
							</td>
						</tr>	
					<?php
					}
				?>
				</table>
			</div>
		<?php
		}
	?>
	
	<div class="clearBoth"></div>
	
</div>
