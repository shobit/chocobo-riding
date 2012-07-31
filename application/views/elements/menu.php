<style>
	.vmenu ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	
	li {clear: right;}
	li:hover {background-color: #eee;}
	li.selected {background-color: #355F9C;}
	li.selected a {color: #fff;}
	li a {color: #333; display: block; padding: 5px 0 5px 5px;}
	li a:hover {text-decoration: none;}
	.rfloat {float: right; margin: -2px 20px 0 0;}
	
	.menutitle {
		margin-top: 10px;
		margin-bottom: -14px;
		font-variant: small-caps;
		font-size: 14px;
		color: #bbb;
	}
</style>

<?php
$url = url::current();
$user = $this->session->get('user');
$chocobo = $this->session->get('chocobo');
if ( ! $user->loaded):
?>

<?php $menus = array(
	'presentation' 	=> 'page/home',
	'connection' 	=> 'users/login',
	'register' 		=> 'users/new',
	'forum' 		=> 'topics',
) ?>
<ul>
	<?php foreach ($menus as $menu => $path): ?>
	<?php
		$selected = (strrpos($url, $path) === FALSE) ? '' : ' class="selected"';
		?>
		<li<?php echo $selected ?>>
			<?php echo html::anchor($path, Kohana::lang("menu.$menu")) ?>
		</li>
	<?php endforeach; ?>
</ul>

<?php else: ?>

<ul id="menu-default">

	<?php
	$selected = ($url !== 'users/' . $user->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px;">
			<?php echo html::anchor('user/logout', html::image('images/icons/logout.png')) ?>
		</div>
		<?php echo $user->link() ?>
	</li>

	<?php
	$selected = ($url !== 'chocobos/' . $chocobo->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px; top: 1px;">
			<?php echo html::anchor('', html::image('images/icons/list-off.png'), array('class' => 'toggle-stable')) ?>
		</div>
		<?php 
		echo html::anchor('chocobos/' . $chocobo->id, $chocobo->name); 
		?>
	</li>

	<?php
	$selected = (strrpos($url, 'races') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>races">
			Courses
			<?php
			/*$nbr_races = ORM::factory('result')
				->where('chocobo_id', $user->chocobo->id)
				->where('seen', FALSE)
				->count_all();
			if ($nbr_races > 0):
			?>
			<div class="rfloat notif not_seen">
				<?php echo $nbr_races ?>
			</div>
			<?php endif; */?>
		</a>
	</li>
	
	<?php
	$selected = (strrpos($url, 'shop') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('shop', 'Boutique'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'inventory') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('inventory', 'Inventaire'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'success/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('success/view/' . $user->username, 'Jockeys'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('rankings', 'Chocobos'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('shoutbox', 'Shoutbox', array('onclick' => 'javascript:openShoutbox(); return false;')); ?>
	</li>
		
	<?php
	$selected = (strrpos($url, "topics") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>topics">
			Forum
			<?php
			$nbr_comments = ORM::factory('comment_notification')
				->where('user_id', $user->id)
				->count_all();
			
			if ($nbr_comments > 0):
			?>
			<div class="rfloat notif not_seen">
				<?php echo $nbr_comments ?>
			</div>
			<?php endif; ?>
		</a>
	</li>
		
	<?php
	$selected = (strrpos($url, "discussions") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>discussions">
			Messages
			<?php
			$nbr_messages = ORM::factory('message_notification')
				->where('user_id', $user->id)
				->count_all();
			
			if ($nbr_messages > 0):
			?>
			<div class="rfloat notif not_seen">
				<?php echo $nbr_messages ?>
			</div>
			<?php endif; ?>
		</a>
	</li>

	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('tutorial', 'Aide'); ?>
	</li>

	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('tutorial', 'A propos'); ?>
	</li>
</ul>

<ul id="menu-stable" style="display: none;">

	<?php
	$selected = ($url !== 'users/' . $user->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px;">
			<?php echo html::anchor('user/logout', html::image('images/icons/logout.png')) ?>
		</div>
		<?php echo $user->link() ?>
	</li>

	<?php
	$selected = ($url !== 'chocobos/' . $chocobo->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px; top: 1px;">
			<?php echo html::anchor('', html::image('images/icons/list-on.png'), array('class' => 'toggle-stable')) ?>
		</div>
		<?php 
		echo html::anchor('chocobos/' . $chocobo->id, $chocobo->name); 
		?>
	</li>

	<?php foreach ($user->chocobos as $c): 
	if ($chocobo->id != $c->id): ?>
	<li>
		<?php echo html::anchor('chocobo/change/' . $c->name, $c->name) ?>
	</li>
	<?php endif;
	endforeach; ?>

</ul>

<?php if ($user->has_role('admin') and FALSE): ?>
<div class="menutitle">Administration</div>
<p>
<ul>
	<?php
	$selected = (strrpos($url, "admin/users") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>admin/users">
			Joueurs
			<div class="rfloat notif">
				<?php 
				$nbr_users = ORM::factory('user')->count_all();
				echo $nbr_users;
				?>
			</div>
		</a>
	</li>
</ul>
</p>
<?php endif; ?>

<script>
$(function(){
	$('.toggle-stable').click(function(){
		$('#menu-default').toggle();
		$('#menu-stable').toggle();
		return false;
	});
});
</script>

<?php endif; ?>
