<h1>Classement de chocobos</h1>
<div id="prelude">Ici, vous pouvez voir les classements relatifs aux chocobos! Vous ne pouvez voir les classements que dans l'ordre décroissant.</div>

<?= $panel ?>

<?php 

if ($pagination->total_pages > 1)
	echo $pagination->render();

foreach ($chocobos as $chocobo) 
{
	?><div class="billet">
		<?php 
			echo $chocobo->display_image('menu');
		?>
		<div class="text">
			<small>#<?= ($debut+1) ?></small>
			<?php 
				echo html::anchor('chocobo/view/'.$chocobo->name, $chocobo->name); //<!-- class="nameLink" --><br />
				echo "<br />";
				echo html::anchor('user/view/'.$chocobo->user->username, $chocobo->user->username); 
				if ($chocobo->user->is_connected()) echo " ".html::image('images/theme/online.png');
			?><br />
			<?php 
				switch($orderby) 
				{
					case 'level':
						echo 'Niveau '.$chocobo->level;
						break;
					case 'fame':
						echo html::image('images/theme/etoile.png').$chocobo->display_fame();
						break;
					case 'max_speed':
						echo $chocobo->max_speed.' km/h';
						break;
					case 'birthday':
						$tl = gen::time_left($chocobo->birthday);
						echo $tl['short'];
						break;
				} 
			?>
		</div>
	</div>
	<?php
	$debut += 1;
	if ($debut%5 == 0) echo '<div class="clearBoth"></div>';
} ?>

<div class="closeBillet"></div>