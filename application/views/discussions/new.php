<style>
	.left {width: 64px; float: left; margin: 5px 0 0 18px;}
	.right {width: 450px; float: left; margin-left: 14px;}
	.middle {width: 470px; float: left;  margin-left: 14px;}
	.right2 {width: 100px; float: left; margin-top: 19px;}

	.message {margin: 20px 0;}
	
	input {color: #333;}
	input[type=text] {width: 450px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	textarea {
		width: 100%; 
		height: 150px; 
		font-size: 11px; 
		outline: none; 
		resize: none;
		border: 1px solid #899BC1; 
		color: #333;
	}
</style>

<?php
echo HTML::style('media/js/lib/markitup/skins/markitup/style.css');
echo HTML::style('media/js/lib/markitup/sets/markdown/style.css');
echo HTML::script('media/js/lib/markitup/jquery.markitup.js');
echo HTML::script('media/js/lib/markitup/sets/markdown/set.js');
?>	

<h2>Nouvelle discussion</h2>

<?php echo Form::open() ?>

<div class="discussion">
	
	<div class="left"></div>
	<div class="right">
		<?php echo Form::input('title', '', array('placeholder' => 'Titre..')) ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php echo Form::input('type', '', array('placeholder' => 'Type..')) ?>
	</div>
	<div class="clearleft"></div>
		
	<?php if ($user->has_role(array('admin', 'modo'))) : ?>
	
		<div class="left"></div>
		<div class="right">
			<?php echo Form::checkbox('locked', '').' ' ?>
			<?php echo Form::label('locked', 'Empêcher les joueurs de commenter ce sujet') ?>
		</div>
		<div class="clearleft"></div>
		
	<?php endif; ?>
	
</div>

<div class="message">
	<div class="left">
	
	</div>
	<div class="middle">
		<?php echo Form::textarea('content', '', array(
        	'id' => 'content',
        	'class' => 'markdown', 
        	'placeholder' => 'Message..',
        )) ?>
	</div>
	<div class="right2">
		<?php echo Form::submit('submit', 'Créer', array(
	    	'id' => 'submit', 
	    	'class' => 'button blue',
	    )) ?>
	</div>
	<div class="clearleft"></div>
</div>
	
<?php 
echo Form::close();
?>

<script>
$(function(){

	$('.markdown').markItUp(mySettings);
		
});
</script>
