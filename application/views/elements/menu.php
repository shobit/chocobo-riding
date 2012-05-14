<style>
	.vmenu ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	
	li {clear: right;}
	li:hover {background-color: #eee;}
	li.selected {background-color: #ddd;}
	li a, li a:visited {color: #333; padding: 5px 0 5px 0; display: block; padding-left: 20px;}
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

<p>
<ul>
	<li><?php echo html::anchor('page/home', Kohana::lang('menu.presentation')); ?></li>
	<li><?php echo html::anchor('users/login', Kohana::lang('menu.connection')); ?></li>
	<li><?php echo html::anchor('users/new', Kohana::lang('menu.register')); ?></li>
	<li><?php echo html::anchor('topics', 'Forum'); ?></li>
	<li><?php echo html::anchor('page/about', 'À propos'); ?></li>
</ul>
</p>

<?php else: ?>

<div style="float:left;">
	<?php 
	echo $user->image('mini');
	?>
</div>

<div style="float:left; margin: 10px 0 0 10px; font-weight: bold;">
	<?php 
	echo $user->link();
	?>
</div>

<div class="clearleft"></div>

<div class="menutitle"><?php echo Kohana::lang('menu.stable'); ?></div>

<p>
<ul>
	<?php
	//$user->reload();
	$c = $this->session->get('chocobo');
	foreach ($user->chocobos as $chocobo)
	{
		$selected = ($c->id !== $chocobo->id) ? '' : ' class="selected"';
		?>
		<li<?php echo $selected ?>>
			<a href="<?php echo url::base() ?>chocobo/change/<?php echo $chocobo->name ?>">
				<?php echo $chocobo->name ?>
				<div class="rfloat notif">
					<?php 
					echo $chocobo->pl;
					?>
				</div>
			</a>
		</li>
		<?php
	}
	?>
</ul>
</p>

<div class="menutitle"><?php echo Kohana::lang('menu.title'); ?></div>

<p>
<ul>
	<?php $url = url::current() ?>
	
	<?php
	$selected = (strrpos($url, 'home') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php //echo html::anchor('page/events', 'Événements') ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'chocobos/' . $user->id) === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php //echo html::anchor('chocobos/' . $user->chocobo->id . '/' . $user->chocobo->name, 'Chocobo'); ?>
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
		<?php echo html::anchor('fusion', 'Enclos'); ?>
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
			$nbr_comments = $this->db
				->from('comments_notifications AS cn')
				->where('cn.user_id', $user->id)
				->join('comments AS c', 
					array(
						'c.id' => 'cn.comment_id'
					), null, 'LEFT')
				->join('topics AS t', 
					array(
						't.id' => 'c.topic_id'
					), null, 'LEFT')
				->where('t.archived', FALSE)
				->count_records();
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
			$nbr_messages = $this->db->from('messages_notifications')
				->where('user_id', $user->id)
				->count_records();
			if ($nbr_messages > 0):
			?>
			<div class="rfloat notif not_seen">
				<?php echo $nbr_messages ?>
			</div>
			<?php endif; ?>
		</a>
	</li>
		
	<li>
	<?php echo html::anchor('users/logout', Kohana::lang('menu.deconnection')); ?>
	</li>
</ul>
</p>

<?php if ($user->has_role('admin')): ?>
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
