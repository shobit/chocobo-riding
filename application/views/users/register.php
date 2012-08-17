<?php echo Form::open() ?>

<?php if ($errors): ?>
<p class="message">Some errors were encountered, please check the details you entered.</p>
<ul class="errors">
<?php foreach ($errors as $message): ?>
    <li><?php echo $message ?></li>
<?php endforeach ?>
<?php endif ?>

<div>
	<?php echo Form::input('username', $values['username'], array('placeholder' => 'Pseudo')) ?>
</div>

<div>
	<?php echo Form::password('password', '', array('placeholder' => 'Mot de passe')) ?></td>
</div>

<div>
	<?php echo Form::password('password_again', '', array('placeholder' => 'Retaper le mot de passe')) ?></td>
</div>

<div>
	<?php echo Form::input('email', $values['email'], array('placeholder' => 'Adresse email', 'style' => 'margin-bottom: 6px;')) ?></td>
</div>

<div>
	<?php echo html::anchor('', "S'inscrire", array('id' => 'submit', 'class' => 'button blue')) ?> 
</div>

<?php echo Form::close() ?>

<script>
$(function(){

	$('a#submit').click(function(){
		$('form').submit();
		return false;
	});

});
</script>
