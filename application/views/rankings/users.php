<h1>Classement de jockeys</h1>
<div id="prelude">Ici, vous pouvez voir les classements relatifs aux jockeys! Êtes-vous bien classé, coubo? Vous devez méritez votre place et ainsi gagner le respect de vos compagnons ;) Vous ne pouvez voir les classements que dans l'ordre décroissant.</div>

<?= $panel ?>

<?php 

if ($pagination->total_pages > 1)
	echo $pagination->render();

foreach ($users as $user) 
{
	?><div class="billet">
		<?php 
			echo $user->display_image('thumbmail');
		?>
		<div class="text">
			<small>#<?= ($debut+1) ?></small>
			<?php 
				echo html::anchor('user/view/'.$user->username, $user->username);
				if ($user->is_connected()) echo " ".html::image('images/icons/online.png');
				echo "<br />";
				echo $user->role(); 
			?><br />
			<?php 
				switch($orderby) 
				{
					case 'fame':
						echo html::image('images/theme/etoile.png').$user->display_fame();
						break;
					case 'gils':
						echo html::image('images/theme/gil.gif').$user->gils.' Gils';
						break;
					case 'registered':
						$tl = gen::time_left($user->registered);
						echo $tl['short'];
						break;
					case 'connected':
						$tl = gen::time_left($user->connected);
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