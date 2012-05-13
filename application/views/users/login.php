<style>
	.user {width: 100%;} 
	.user .left {width: 164px; float: left; margin: 5px 0 0 18px;}
	.user .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.user input[type=text], .user input[type=password] {width: 250px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<h1><?php echo Kohana::lang('user.title_login') ?></h1>

<?php echo form::open('users/login') ?>
	
<div class="user">
	
	<div class="left">
		<?php echo form::label('username', 'Login de connexion') ?>
	</div>
	<div class="right">
		<?php echo form::input('username') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left">
		<?php echo form::label('password', 'Mot de passe') ?>
	</div>
	<div class="right">
		<?php echo form::password('password') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php
		echo form::submit(array(
			'name' => 'submit', 
			'id' => 'submit', 
			'class' => 'button blue',
			'value' => "Se connecter"
		));
		echo html::anchor('users/new', "S'inscrire", array('class' => 'button grey'));
		?>
	</div>
	<div class="clearleft"></div>

</div>

<?php echo form::close() ?>
