<style>
	input[type=text], input[type=password] 
		{width: 300px; font-size: 14px; outline: none; padding: 6px; border: 1px solid #899BC1;}
	input.button {}
</style>

<?php echo form::open(); ?>
	
<div>
	<?php echo form::input('username', $form['username'], 'placeholder="Pseudo"') ?>
</div>

<div>
	<?php echo form::password('password', $form['password'], 'placeholder="Mot de passe"') ?></td>
</div>

<div>
	<?php echo form::password('password_again', $form['password_again'], 'placeholder="Retaper le mot de passe"') ?></td>
</div>

<div>
	<?php echo form::input('email', $form['email'], 'placeholder="Adresse email"') ?></td>
</div>

<div>
	<?php echo html::anchor('', "S'inscrire", array('id' => 'submit', 'class' => 'button')); ?> 
</div>

<?php 
echo form::close();
?>

<script>
$(function(){

	$('a#submit').click(function(){
		$('form').submit();
		return false;
	});

});
</script>
