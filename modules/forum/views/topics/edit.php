<style>
	.topic {width: 100%;} 
	.topic .left {width: 64px; float: left; margin: 5px 0 0 18px; font-weight: bold; font-variant: small-caps; text-align: right;}
	.topic .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.topic input {color: #333;}
	.topic input[type=text] {width: 450px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	.hidden {display: none;}

	.comment {width: 100%; margin: 20px 0 20px 0;}
	.comment .avatar {width: 30px; float: left; margin: 14px 0 0 18px;}
	.comment .textarea {float: left; width: 450px; margin-left: 14px; position: relative;}
	.comment textarea {width: 450px; height: 150px; font-size: 11px; outline: none; resize: none; padding: 3px; border: 1px solid #899BC1; color: #333;}
	.comment .submit {float: left; margin-top: 19px;}
</style>

<?php
echo html::stylesheet('js/lib/markitup/skins/markitup/style.css', 'screen', false);
echo html::stylesheet('js/lib/markitup/sets/markdown/style.css', 'screen', false);
echo html::script('js/lib/markitup/jquery.markitup.js');
echo html::script('js/lib/markitup/sets/markdown/set.js');
require_once Kohana::find_file('libraries', 'markdown');
?>	

<?php
if ($form['topic']['id'] == 0)
{
	?><h2>Nouveau sujet</h2><?php
	echo form::open('topics/new');
}
else
{
	?><h2>Editer un sujet</h2><?php
	echo form::open('topics/' . $form['topic']['id'] . '/edit');
}
?>

<div class="topic">
	
	<div class="left">titre</div>
	<div class="right">
		<?php echo form::input('title', $form['topic']['title']) ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left">type</div>
	<div class="right">
		<?php echo form::input('type', $form['topic']['type']) ?>
	</div>
	<div class="clearleft"></div>
		
	<?php if ($user->has_role(array('admin', 'modo'))) : ?>
	
		<div class="left"></div>
		<div class="right">
			<?php echo form::checkbox('locked', '', $form['topic']['locked']).' ' ?>
			<?php echo form::label('locked', 'Empêcher les joueurs de commenter ce sujet') ?>
		</div>
		<div class="clearleft"></div>
		
	<?php endif; ?>
	
</div>

<div class="comment">
	<div class="avatar">
	
	</div>
	<div class="textarea">
		<?php echo form::textarea(array(
        	'id' => 'content',
        	'class' => 'markdown', 
        	'name' => 'content',
        	'placeholder' => 'Commentaire..',
        	'value' => $form['comment']['content']
        )) ?>
	</div>
	<div class="submit">
		<?php echo form::submit(array(
	    	'name' => 'submit', 
	    	'id' => 'submit', 
	    	'class' => 'button blue',
	    	'value' => 'Créer'
	    )) ?>
	</div>
	<div class="clearleft"></div>
</div>
	
<?php 
echo form::close();
?>

<script>
$(function(){

	$('.markdown').markItUp(mySettings);

	$('input[name=title], input[name=tags], textarea[name=content]')
		.focus(function(){
			if ($(this).val() == '') {
				$(this).next().hide();
			}
		})
		.blur(function(){
			if ($(this).val() == '') {
				$(this).next().show();
			}
		});
		
});
</script>
