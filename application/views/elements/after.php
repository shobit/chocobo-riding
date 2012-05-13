<?php

$user = $this->session->get('user');
if ($user->id >0) {
	?>
	
	<table class="barre">
	
		<tr>
		
			<td>
			
				<table>
				
					<tr>
		
		<td class="icons">
			<?= $user->image("mini", array('width'=>20)) ?>
		</td>
		<td>
			<?= html::anchor('/user/view/'.$user->username, $user->username); ?>
		</td>
		
		<td class="icons">
			<?= html::image('images/theme/gil.gif') ?></td>
		<td>
			<small><?= html::anchor('error', $user->gils.' Gils') ?></small>
		</td>
            
		<?php 
		$nbr_topics = count($user->notifs);
		if ($nbr_topics > 0) {
			$icon = "post";
			if ($nbr_topics > 0) $icon .= "_new";
			if ($nbr_topics > 0) $res = "<b>";
			$res .= $nbr_topics;
			if ($nbr_topics > 0) $res .= "<b>";
			?>
			<td class="icons"><?= html::image('images/icons/'.$icon.'.png') ?></td>
			<td><?= html::anchor('forum', "Forum <small>(".$nbr_topics.")</small>") ?></td>
			<?php
		} else {
			?>
			<td class="icons"><?= html::image('images/icons/post.png') ?></td>
			<td><?= html::anchor('forum', "Forum") ?></td>
			<?php
		}
		?> 
		
					</tr>
					
				</table>
			
			</td>
			
			<td id="after-right">
			
				<table>
					
					<tr>	
		
		<?php
		$tps = time()-5*60;
		$nbr_users = ORM::factory('user')->where('activated', 1)->count_all();
		$users_connected = ORM::factory('user')->where('connected>', $tps)->count_all();
		$site = ORM::factory('site')->find(1);
		$record = false; $egal = false;
		$couleur = "gris";
		if ($users_connected > $site->max_connected) 
		{
			$site->max_connected = $users_connected;
			$site->time_connected = time();
			$site->save();
			
			
		}
		if ($users_connected >= $site->max_connected) 
			$couleur = (time() - $site->time_connected < 3600) ? "rouge" : "bleu";
		
		?>
		<td class="icons"><?= html::image('images/menu/connectes.gif') ?></td>
		<td><?php 
		
		echo html::anchor(
			'rankings/users/connected', 
			'Connectés <small>(<big><span class="line_'.$couleur.'"><b>'.$users_connected.
				'</b></span></big>/'.$nbr_users.')</small>'
		);
		
		?></td>
		
		<td class="icons"><?= html::image('images/forum/types/bug.png') ?></td>
		<td><?= html::anchor('forum/bug/add', 'Reporter un bug') ?></td>
		
		<td class="icons"><?= html::image('images/menu/presentation.gif') ?></td>
		<td><?= html::anchor('user/logout', 'Déconnexion') ?></td>
		
					</tr>
					
				</table>
			
			</td>
		
		</tr>
		
	</table>
	
	<?php

} else {

?>

	<table class="barre">
	
		<?php
			
		$tps = time()-5*60;
		$nbr_users = ORM::factory('user')->where('activated', 1)->count_all();
		$users_connected = ORM::factory('user')->where('connected>', $tps)->count_all();
		
		?>
		<td class="icons"><?= html::image('images/menu/connectes.gif') ?></td>
		<td><?= html::anchor('error', 
			'Connectés <small>('.$users_connected.'/'.$nbr_users.')</small>') ?></small></td>
	
	</table>

<?php

	}

?>
