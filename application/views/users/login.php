<?php echo form::open() ?>

<div>
	<?php echo form::input('username', '', 'placeholder="Pseudo"') ?>
</div>

<div>
	<?php echo form::password('password', '', 'placeholder="Mot de passe" style="margin-bottom: 6px;"') ?>
</div>

<div>
	<?php echo html::anchor('', "Se connecter", array('id' => 'submit', 'class' => 'button green')); ?> 
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
