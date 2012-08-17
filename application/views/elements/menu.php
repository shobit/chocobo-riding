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
	.rfloat {float: right; margin: -1px 10px 0 0;}
	
	.menutitle {
		margin-top: 10px;
		margin-bottom: -14px;
		font-variant: small-caps;
		font-size: 14px;
		color: #bbb;
	}
</style>

<?php
$url = Request::current()->uri();
$user = Auth::instance()->get_user();
$chocobo = Session::instance()->get('chocobo');
if ($user === FALSE):
?>

<?php $menus = array(
	'Présentation' 	=> array('home', '/'),
	'Connexion' 	=> array('login'),
	'Inscription' 	=> array('register'),
	'Discussions' 		=> array('discussions'),
	'A propos' 		=> array('about'),
) ?>
<ul>
	<?php foreach ($menus as $menu => $path): ?>
		<?php
		$selected = (in_array($url, $path)) ? ' class="selected"' : '';
		?>
		<li<?php echo $selected ?>>
			<?php echo HTML::anchor($path[0], __($menu)) ?>
		</li>
	<?php endforeach; ?>
</ul>

<?php else: ?>

<ul id="menu-1">

	<?php
	$selected = ($url !== 'users/' . $user->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px;">
			<?php echo HTML::anchor('logout', HTML::image('images/icons/logout.png'), 
				array('class' => 'logout', 'original-title' => 'Se déconnecter')) ?>
		</div>
		<?php echo $user->link() ?>
	</li>

	<?php
	$selected = ($url !== 'chocobos/' . $chocobo->id) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px; top: 1px;">
			<?php echo HTML::anchor('', HTML::image('images/icons/list-off.png'), 
				array('class' => 'toggle-stable', 'original-title' => "Voir/cacher l'écurie")) ?>
		</div>
		<?php 
		echo HTML::anchor('chocobos/' . $chocobo->id, $chocobo->name); 
		?>
	</li>

</ul>

<ul id="menu-2">

	<?php
	$selected = (strrpos($url, 'races') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>races">
			Courses
		</a>
	</li>
	
	<?php
	$selected = (strrpos($url, 'fusion') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('fusion', 'Fusion'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'shop') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('shop', 'Boutique'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'inventory') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('inventory', 'Inventaire'); ?>
	</li>
	
	<?php
	$selected = ((strrpos($url, 'users') !== FALSE) and ($url !== 'users/' . $user->id)) ? ' class="selected"' : '';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>users">
			Jockeys
			<?php
			$tps = time() - 5*60;
			$nbr_connected = ORM::factory('user')
				->where('connected', '>', $tps)
				->count_all();
			if ($nbr_connected > 1):
			?>
			<div class="rfloat notif">
				<?php echo $nbr_connected - 1 ?>
			</div>
			<?php endif; ?>
		</a>
	</li>
	
	<?php
	$selected = ((strrpos($url, 'chocobos') !== FALSE) and ($url !== 'chocobos/' . $chocobo->id)) ? ' class="selected"' : '';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('chocobos', 'Chocobos'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, "shoutbox") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?> style="position: relative;">
		<div style="width: 16px; position: absolute; left: -25px; top: 1px;">
			<?php echo HTML::anchor('', HTML::image('images/icons/link.png'), 
				array('onclick' => 'openShoutbox(); return false;', 'class' => 'shoutbox', 'original-title' => 'Ouvrir en pop-up')) ?>
		</div>
		<?php echo HTML::anchor('shoutbox', 'Shoutbox'); ?>
	</li>
		
	<?php
	$selected = (strrpos($url, "discussions") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>discussions">
			Discussions
			<?php
			$nbr_messages = $user->message_notifications->count_all();
			
			if ($nbr_messages > 0):
			?>
			<div class="rfloat notif not_seen">
				<?php echo $nbr_messages ?>
			</div>
			<?php endif; ?>
		</a>
	</li>

	<?php 
	$selected = (strrpos($url, 'developers') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('developers', 'Développeurs'); ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'about') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('about', 'A propos'); ?>
	</li>

	<?php
	$selected = (strrpos($url, 'help') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo HTML::anchor('help', 'Aide'); ?>
	</li>
</ul>

<ul id="menu-3" style="display: none;">

	<?php foreach ($user->chocobos->find_all() as $c): 
	if ($chocobo->id != $c->id): ?>
	<li>
		<?php echo HTML::anchor('chocobos/'.$c->id.'/change', $c->name) ?>
	</li>
	<?php endif;
	endforeach; ?>

</ul>

<?php if (FALSE): ?>
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
		if ($('#menu-2').is(':visible')) {
			$('#menu-2').hide();
			$('#menu-3').slideToggle();
		} else {
			$('#menu-3').hide();
			$('#menu-2').slideToggle();
		}
		return false;
	});

	$('.logout').tipsy({gravity: 'e'});
	$('.toggle-stable').tipsy({gravity: 'e'});
	$('.shoutbox').tipsy({gravity: 'e'});
});
</script>

<?php endif; ?>
