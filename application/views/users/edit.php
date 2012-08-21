<h2>
	<?php echo HTML::anchor('users', __('Jockeys')) ?> &rarr; 
	<?php echo $user->link() ?> &rarr; Préférences
</h2>

<div class="nav">
	<?php echo HTML::anchor('#/avatar', 'Avatar') ?>
	<?php echo HTML::anchor('#/email', 'Email') ?>
	<?php echo HTML::anchor('#/password', 'Mot de passe') ?>
</div>

<div class="section" id="avatar">
	<?php echo Form::open(NULL, array('enctype' => 'multipart/form-data')) ?>

	<?php echo Form::hidden('type', 'avatar') ?>

	<table class="table1">

		<tr>
			<th class="len150"></th>
			<th class="lenmax"></th>
		</tr>
		
		<tr>
			<td><?php echo __('Avatar') ?></div>
			<td><?php echo Form::file('image') ?></td>
			<td></td>
		</tr>
		
	</table>

	<?php 
	echo Form::submit('submit', __('Valider'), array('class'=>'button blue')); 

	echo Form::close();

	?>
</div>

<div class="section" id="email">
	<?php echo Form::open() ?>

	<?php echo Form::hidden('type', 'email') ?>

	<table class="table1">

		<tr>
			<th class="len150"></th>
			<th class="lenmax"></th>
		</tr>
		
		<tr>
			<td><?php echo __('Email') ?></div>
			<td>
				<?php 
				echo Form::input('email', $user->email); 

				if ($user->email_verified == FALSE)
				{
					echo __('Vérifiez cette adresse en la validant une nouvelle fois.');
				}
				?>
			</td>
			<td></td>
		</tr>
		
	</table>

	<?php 
	echo Form::submit('submit', __('Valider'), array('class'=>'button blue')); 

	echo Form::close();

	?>
</div>

<div class="section" id="password">
	<?php echo Form::open() ?>

	<?php echo Form::hidden('type', 'password') ?>

	<table class="table1">

		<tr>
			<th class="len150"></th>
			<th class="lenmax"></th>
		</tr>
		
		<tr class="divChangePassword">
			<td><?php echo __('Mot de passe actuel : ') ?></div>
			<td>
				<?php echo Form::password('password_old') ?>
			</td>
			<td></td>
		</tr>
			
		<tr class="divChangePassword">
			<td><?php echo Form::label('password_new', __('Nouveau mot de passe : ')) ?></div>
			<td>
				<?php echo Form::password('password') ?>
			</td>
			<td></td>
		</tr>	

		<tr class="divChangePassword">
			<td><?php echo Form::label('password_again', __('Répéter le mot de passe : ')) ?></td>
			<td>
				<?php echo Form::password('password_again') ?>
			</td>
			<td></td>
		</tr>
		
	</table>

	<?php 
	echo Form::submit('submit', __('Valider'), array('class'=>'button blue')); 

	echo Form::close();

	?>
</div>

<script>
$(document).ready(function(){

	init_nav('#/avatar');
	
});
</script>

