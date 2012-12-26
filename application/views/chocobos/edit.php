<?php echo Form::open() ?>
<p>
<b><?php echo __('Nom de votre chocobo :') ?></b>

<?php echo Form::input('name', $values['name'], array('placeholder' => 'Nom du chocobo')) ?>
(<?php echo html::image('images/icons/'.$chocobo->display_gender('code').'.png').' '.$chocobo->display_gender('zone') ?>)
<br />

<small><?php echo __("Vous pouvez changer le nom de votre chocobo! Il ne peut pas y avoir plusieurs chocobos portant le même nom. " 
					."Changer le nom d'un chocobo n'est pas sans conséquences. Etant bébé chocobo, vous pouvez changer autant de fois "
					."que vous voulez.") ?>
</small>
</p>

<p>
<?php echo html::anchor('', "Valider", array('id' => 'submit', 'class' => 'button blue')) ?> 
</p>
<?php echo Form::close() ?>

<script>
$(function(){
	$('a#submit').click(function(){
		$('form').submit();
		return false;
	});

});
</script>