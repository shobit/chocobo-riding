<style>
	.discussion {width: 100%;} 
	.discussion .left {width: 64px; float: left; margin: 14px 0 0 18px;}
	.discussion .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.discussion input {color: #333;}
	.discussion input[type=text] {width: 450px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}

	.message {width: 100%; margin: 20px 0 20px 0;}
	.message .avatar {width: 64px; float: left; margin: 14px 0 0 18px;}
	.message .textarea {float: left; width: 450px; margin-left: 14px; position: relative;}
	.message textarea {width: 450px; height: 150px; font-size: 11px; outline: none; resize: none; padding: 3px; border: 1px solid #899BC1; color: #333;}
	.message .submit {float: left;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<div class="clearleft"></div>

<h1>Envoyer un nouveau message</h1>
<?php
echo form::open('discussions/new');
?>

<div class="discussion">
	
	<div class="left"></div>
	<div class="right">
		<?php echo form::input('receiver') ?>
		<span class="holder"><?php echo Kohana::lang('discussion.labels.receiver') ?></span>
	</div>
	<div class="clearleft"></div>
	
</div>

<div class="message">
	<div class="avatar">
		
	</div>
	<div class="textarea">
		<?php echo form::textarea(array(
        	'id' => 'textile', 
        	'name' => 'content'
        )) ?>
		<span class="holder"><?php echo Kohana::lang('discussion.labels.message') ?></span>
	</div>
	<div class="submit">
		<?php echo form::submit(array(
	    	'name' => 'submit', 
	    	'id' => 'submit', 
	    	'class' => 'button blue',
	    	'value' => 'Envoyer'
	    )) ?>
	</div>
	<div class="clearleft"></div>
</div>
	
<?php 
echo form::close();
?>

<script>
$(function(){

	$('.holder').click(function(){
		$(this).hide();
		$(this).prev().focus();
	});
	
	$('input[name=receiver], textarea[name=content]')
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
