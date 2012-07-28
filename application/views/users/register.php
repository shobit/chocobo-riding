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
	<?php echo form::input('email', $form['email'], 'placeholder="Adresse email" style="margin-bottom: 6px;"') ?></td>
</div>

<div>
	<?php echo html::anchor('', "S'inscrire", array('id' => 'submit', 'class' => 'button blue')); ?> 
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
