<?php echo form::open_multipart('user/edit', array('class'=>'frm_edits')); ?>

<h1><?= Kohana::lang('user.edit.title') ?></h1>
<div id="prelude"><?= Kohana::lang('user.edit.prelude') ?></div>

<div class="leftPart2">

<?php echo form::open_multipart('user/edit', array('class'=>'frm_edits')); ?>

	<div class="label"><?= Kohana::lang('user.form.username') ?></div>
	<div class="value">
		<?php echo $user->username; ?>
	</div>
	
	<div class="hr"></div>
	
	<div id="divNoChangePassword">
		<div class="label"><?= Kohana::lang('user.form.password0') ?></div>
		<div class="value">
			******** <?php echo html::anchor('#changePassword', 'changer', 
				array('id'=>'changePassword', 'class'=>'button')); ?>
		</div>
	</div>
	
	<div id="divChangePassword" style="display:none;">
		<div class="label"><?= Kohana::lang('user.form.password') ?></div>
		<div class="value">
			<?= form::password('password') ?>
		</div>
		
		<div class="label"><?= form::label('password_new', Kohana::lang('user.form.password_new')) ?></div>
		<div class="value">
			<?= form::password('password_new') ?>
		</div>
		
		<div class="label"><?= form::label('password_again', Kohana::lang('user.form.password_again')) ?></div>
		<div class="value">
			<?= form::password('password_again') ?>
		</div>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= Kohana::lang('user.form.email') ?></div>
	<div class="value">
		<?php echo $user->email; ?>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= Kohana::lang('user.form.image') ?></div>
	<div class="value">
		<?php echo form::upload('image'); ?>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= form::label('gender', Kohana::lang('user.form.gender')) ?></div>
	<div class="value">
		<?php
		$options = array(
			Kohana::lang('user.gender.unknown'), 
			Kohana::lang('user.gender.man'), 
			Kohana::lang('user.gender.girl'));
		echo form::dropdown('gender', $options, $user->gender);
		?>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= Kohana::lang('user.form.birthday') ?></div>
	<div class="value">
		<?php echo form::input('birthday', $user->birthday); ?>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= form::label('locale', Kohana::lang('user.form.locale')) ?></div>
	<div class="value">
		<?php
		$options = gen::languages();
		echo form::dropdown('locale', $options, $user->locale);
		?>
	</div>
	
	<div class="hr"></div>
	
	<div class="label"><?= form::label('notif_forum', Kohana::lang('user.form.notif_forum')) ?></div>
	<div class="value">
		<?php 
		$options = array(
			'Désactiver', 
			'Activer');
		echo form::dropdown('notif_forum', $options, $user->notif_forum);
		?>
	</div>
	
	<?php if ( ! empty($user->api)): ?>
		<div class="hr"></div>
		
		<div class="label"><?= Kohana::lang('user.form.api') ?></div>
		<div class="value">
			<b><?= $user->api ?></b>
		</div>
	<?php endif; ?>
	
	<div class="hr"></div>
	
	<div class="label">&nbsp;</div>
	<div class="value">
		<?php 
		echo form::submit(array('name'=>'submit', 'value'=>'Valider', 'class'=>'button blue')); 
		echo html::anchor('user/view/'.$user->username, 'retour', array('class'=>'button'));
		//echo html::anchor('user/delete', 'Supprimer son compte', array('class'=>'button')); 
		?>
	</div>
	
<?= form::close() ?>
	
</div>

<div class="leftPart">

	<!-- IMAGE -->
	<center>
		<p><?= $user->display_image('thumbmail') ?></p>
	</center>

</div>

<div class="clearBoth"></div>

<script>
$(document).ready(function(){
	
	$('#changePassword').click(function(){
		$('#divNoChangePassword').slideUp(function(){
			$('#divChangePassword').slideDown();
		});
		return false;
	});
	
	$('#changeAvatar').click(function(){
		return false;
	});
	
	$('#deleteAvatar').click(function(){
		//$(this).hide();
		return false;
	});
	
});
</script>

