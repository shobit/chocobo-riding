<style>
	.infos {margin: 20px 0 0 0;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_discussions {font-size: 16px;}
	.infos .nbr_discussions .nbr {color: #999;}
	
	.messages {width: 100%; margin: 0 0 20px 0; color: #333; border-bottom: 1px solid #e9e9e9;}
	.solidborder {border-top: 1px solid #e9e9e9;}
	.dottedborder {border-top: 1px dotted #f5f5f5;}
	.message.not_seen {background-color: #fee;}
	.message {position: relative;}
	.message .avatar {width: 30px; float: left; margin: 14px 0 0 18px;}
	.message .right {width: 600px; float: left; margin: 14px 0 14px 14px;}
	.message .author {margin: 1px 0 1px 0; font-weight: bold;}
	.message .title {font-weight: bold; line-height: 1.28; margin: 5px 0 5px 0;}
	.message .text {line-height: 1.28; margin: 5px 0 5px 0;}
	.message .form {display: none; margin: 5px 0 5px 0;}
	.message .footer {color: #999; line-height: 17px;}
	.message .icon {margin-bottom: -3px; cursor: pointer;}
	.message .icon2 {margin: 0 3px -2px 0;}
	
	.reply {width: 100%; margin: 20px 0 20px 0;}
	.reply .avatar {width: 30px; float: left; margin: 14px 0 0 18px;}
	.reply .textarea {float: left; width: 450px; margin-left: 14px;}
	.reply textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1; color: #333;}
	.reply .submit {float: left; margin-top: 19px;}
	
	.reply2 {width: 100%; margin: 5px 0 5px 0;}
	.reply2 .textarea {float: left; width: 450px;}
	.reply2 textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1;}
	.reply2 .submit {float: left; margin-top: 11px;}
	
	.message .favon {font-weight: bold; font-style: italic; color: #333;}
	.message .hidden {display: none;}
</style>

<?php
echo HTML::style('media/js/lib/markitup/skins/markitup/style.css');
echo HTML::style('media/js/lib/markitup/sets/markdown/style.css');
echo HTML::script('media/js/lib/markitup/jquery.markitup.js');
echo HTML::script('media/js/lib/markitup/sets/markdown/set.js');
?>	

<h1>Forum</h1>

<div class="clearright"></div>

<div class="messages">
	<?php
	$last_user_id = 0;
	$dark = "";
	$ms = $discussion->messages->find_all();
	echo View::factory('discussions/message')
		->set('user', $user)
		->set('discussion', $discussion)
		->set('message', $ms[0])
		->set('first_message', TRUE)
		->set('last_user_id', $last_user_id)
		->set('dark', $dark);
	?>
</div>

<div class="infos">
		
	<div class="pages">
		<b><?php echo $pagination->render() ?></b>
	</div>
	
	<div class="nbr_discussions">
		<?php 
		echo ' <span class="nbr">' . $nbr_messages . '</span> ';
		echo Kohana::message('discussions', Inflector::plural('message', $nbr_messages));
		?>
	</div>
	
</div>
	
<div class="comments">
	<?php
	$tr = 0;
	$last_user_id = 0;
	$last_dark = " error";
	foreach ($messages as $message) 
	{
		
		$inc = true;
		$dark = ($tr % 2 == 0) ? ' dark' : '';
		//if ($message->user->has_role('modo')) { $dark = ' modo'; $inc = false; }
		//if ($message->user->has_role('admin')) { $dark = ' admin'; $inc = false; }
		if ($last_user_id == $message->user_id) { $dark = $last_dark; $inc = false; }
		if ($inc) 
		{
			$tr ++;
		}

		echo View::factory('discussions/message')
			->set('user', $user)
			->set('discussion', $discussion)
			->set('message', $message)
			->set('first_message', FALSE)
			->set('last_user_id', $last_user_id)
			->set('dark', $dark);

		$last_user_id = $message->user_id;
		$last_dark = $dark;
	}
	?>
</div>

<?php if ( ($user AND ($user->has_role('modo') OR ! $discussion->locked)) ) : ?>

	<?php echo Form::open('messages/new') ?>
	<?php echo Form::hidden('discussion_id', $discussion->id) ?>
	<div class="reply">
		<div class="avatar">
		
		</div>
		<div class="textarea">
			<?php echo Form::textarea('content', '', array(
	        	'class' => 'markdown', 
	        	'placeholder' => 'Un commentaire ?',
	        )) ?>
	    </div>
		<div class="submit">
			<?php echo Form::submit('submit', __('Poster'), array(
		    	'id' => 'submit', 
		    	'class' => 'button blue',
		    )) ?>
		</div>
	</div>
	<?php echo Form::close() ?>	
	<div class="clearleft"></div>
	
<?php endif; ?>

<script>

$(function(){

	$('.markdown').markItUp(mySettings);

	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	// Afficher les +1/-1
	$('.message').hover(function(){
		$(this).find('.nbfavsw').show();
		$(this).find('.favw').show();
		$(this).find('.options').fadeIn();
	}, function(){
		var nbfavsw = $(this).find('.nbfavs');
		if (nbfavsw.text() == '+0') {
			$(this).find('.nbfavsw').hide();
		}
		$(this).find('.favw').hide();
		$(this).find('.options').hide();
	});
	
	// mettre en favori un topic
	$('.fav').click(function(){
		var id = $(this).attr('id').substring(3);
		$('#fav' + id).attr('src', baseUrl + 'images/forum/loading.gif');
		$.ajax({
			type: 'POST',
			url: baseUrl + 'comments/' + id + '/favorite',
			dataType: 'json',
			success: function(data) {
				var nbfavs = parseInt($('#nbfavs' + id).text());
				nbfavs = (data.icon == 'new') ? nbfavs + 1: nbfavs - 1;
				$('#nbfavs' + id).text('+' + nbfavs);
				
				$('#fav' + id).attr('src', baseUrl + 'images/forum/star-' + data.icon + '.png');
			}
		});
		return false;
	});
	
	$('.edit').click(function(){
		var comment_id = $(this).attr('id').substring(4);
		$('#message' + comment_id).find('.text').toggle();
		$('#message' + comment_id).find('.form').toggle();
		return false;
	});
	
	$('.edit_msg').click(function(){
		var id = $(this).attr('id').substring(1);
		var content = $('#c' + id + ' textarea[name=content-edit]').val();
		$.post(baseUrl + 'messages/' + id + '/edit', {'content': content}, function(data){
			console.log(data);
			$('#c' + id + ' .form').hide();
			$('#c' + id + ' .text').show().html(data.text);
			$('#c' + id + ' .footer').show();
			$('#c' + id + ' .footer .date').html(data.date + ' (modification)');
		});
		return false;
	});
	
	$('.cancel_msg').click(function(){
		var id = $(this).attr('id');
		$('#' + id + ' .form').hide();
		$('#' + id + ' .text').show();
		$('#' + id + ' .footer').show();
		return false;
	});
	
	$('.delete_topic').click(function(){
		var topic_id = $(this).attr('id').substring(5);
		$.post(baseUrl + 'topics/delete', {'id': topic_id}, function(data){
			if (data.success) {
				location.href = baseUrl + 'topics';
			}
		});
		return false;
	});
	
});

</script>