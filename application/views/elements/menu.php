<style>
	.vmenu ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	
	li {clear: right; padding: 5px 0 5px 0; }
	li:hover {background-color: #eee;}
	li.selected {background-color: #355F9C;}
	li.selected a {color: #fff;}
	li a {color: #333; display: block;}
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
$user = $this->session->get('user');
if ( ! $user->loaded):
?>

<ul>
	<li><?php echo html::anchor('page/home', Kohana::lang('menu.presentation')); ?></li>
	<li class="selected"><?php echo html::anchor('users/login', Kohana::lang('menu.connection')); ?></li>
	<li><?php echo html::anchor('users/new', Kohana::lang('menu.register')); ?></li>
	<li><?php echo html::anchor('topics', 'Forum'); ?></li>
	<li><?php echo html::anchor('page/about', 'À propos'); ?></li>
</ul>

<?php else: ?>

<ul>
	<?php $url = url::current() ?>
	
	<?php
	$selected = (strrpos($url, 'page/events') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('page/events', 'Améliorations') ?>
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
	$selected = (strrpos($url, 'fusion') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('fusion', 'Fusion'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'success/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('success/view/' . $user->username, 'Succès'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('rankings', 'Classements'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'user/') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('tutorial', 'Tutorial'); ?>
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

<?php endif; ?>
