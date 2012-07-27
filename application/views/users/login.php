<style>
	.user {width: 100%;} 
	.user input[type=text], .user input[type=password] {width: 300px; font-size: 14px; outline: none; padding: 6px; border: 1px solid #899BC1;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<!--h1><?php echo Kohana::lang('user.title_login') ?></h1-->

<?php echo form::open('users/login') ?>
	
<div class="user">
	
	<div>
		<?php echo form::input('username', '', 'placeholder="Pseudo ou adresse email"') ?>
	</div>
	
	<div>
		<?php echo form::password('password', '', 'placeholder="Mot de passe"') ?>
	</div>

	<div>
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

</div>

<?php echo form::close() ?>
