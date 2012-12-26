<?php echo Form::open() ?>
<div>
	<?php echo Form::input('username', $values['username'], array('placeholder' => 'Pseudo')) ?>
</div>

<div>
	<?php echo Form::password('password', '', array('placeholder' => 'Mot de passe')) ?>
</div>

<div>
	<?php echo HTML::anchor('', "Se connecter", array('id' => 'submit', 'class' => 'button green')); ?> 
</div>
<?php 
echo Form::close();
?>

<script>
$(function(){
	$('a#submit').click(function(){
		$('form').submit();
		return false;
	});

});
</script>
