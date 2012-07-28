<style>
	input[type=text], input[type=password] 
		{width: 300px; font-size: 14px; outline: none; padding: 6px; border: 1px solid #899BC1;}
</style>

<?php echo form::open() ?>

<div>
	<?php echo form::input('username', '', 'placeholder="Pseudo ou adresse email"') ?>
</div>

<div>
	<?php echo form::password('password', '', 'placeholder="Mot de passe"') ?>
</div>

<div>
	<?php echo html::anchor('', "Se connecter", array('id' => 'submit', 'class' => 'button')); ?> 
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
