<?php

/*

if (adminRights()) {
	$texte = "Are You Sure?";
	e($html->link($html->image('forum/delete.gif'), 
		array(
			'view'=>'delete', 
			'user_id'=>$id),
		array(
			'title'=>'Supprimer', 
			'onClick'=>'javascript:return confirme(\''.$texte.'\');'
		)));
}

*/

?>

<h1>Profil de user</h1>
<div id="prelude"></div>

	<div class="leftPart">
			
		<table class="informations">
		
			<tr>
				<td class="label">Nom</td>
				<td class="value">
					<?= $user->username ?>
				</td>
			</tr>
			<tr>
				<td class="label">Statut</td>
				<td class="value">
					<?= $user->role() ?>
				</td>
			</tr>
			<tr>
				<td class="label">Genre</td>
				<td class="value">
					<?= $user->display_gender() ?>
				</td>
			</tr>
			<tr>
				<td class="label">Langue</td>
				<td class="value">
				<?= $user->locale ?>
				</td>
			</tr>
			<?php if (!empty($user->birthday)) { ?>
			<tr>
				<td class="label">Naissance</td>
				<td class="value">
					<?= $user->birthday ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td class="label">Argent</td>
				<td class="value">
				<?php
					echo html::image('images/theme/gil.gif');
					echo $user->gils;
				?>
				</td>
			</tr>
			<tr>
				<td class="label">Succès</td>
				<td class="value">
				<?php
					echo html::image('images/theme/etoile.png');
					$nbr_titles = ORM::factory('title')->count_all();
					$content = count($user->successes).'<small>/'.$nbr_titles.'</small>';
					echo html::anchor('success/view/'.$user->username, $content);
				?>
				</td>
			</tr>
			<tr>
				<td class="label">Histoire</td>
				<td class="value">
				<?php
					if ($user->quest == 0) echo 'Prologue';
					else echo 'Chapitre '.$user->quest;
				?>
				</td>
			</tr>
			<tr>
				<td class="label">Inscrit</td>
				<td class="value">
				<?php
					$tl = gen::time_left($user->registered);
					echo $tl['short'];
				?>
				</td>
			</tr>
			<tr>
				<td class="label">Connecté</td>
				<td class="value">
				<?php
					$tl = gen::time_left($user->connected);
					echo $tl['short'];
				?>
				</td>
			</tr>
	
		</table>
	
	</div>
	
	<div class="leftPart">
	
		<fieldset class="listChocobos">
			<?php 
				echo '<legend>Ecurie <small>('.count($user->chocobos).'/'.$user->boxes.' chocobos)</small></legend>';
			?>
			<table class="form">
				<?php foreach($user->chocobos as $n => $chocobo) { ?>
				<tr>
					<td class="label"><?php //html::image($chocobo->displayImage("small"))); ?></td>
					<td class="value"><?= html::anchor('chocobo/view/'.$chocobo->name, 
						' Box n°'.($n+1).': <b>'.$chocobo->name.'</b>'); ?></td>
				</tr>
				<?php } ?>
			</table>
		</fieldset>
		
	</div>
	
	<div class="leftPart">
	
		<!-- IMAGE -->
		<center>
			<?php $image = $user->image; ?>
			<p><?= $user->image('thumbmail'); ?></p>
			<p><?php echo html::anchor('/user/edit', 'préférences', array('class'=>'button')); ?></p>
		</center>
	
	</div>
	
	<div class="clearBoth"></div>
