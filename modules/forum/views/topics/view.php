<style>
	.infos {margin: 20px 0 0 0;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_topics {font-size: 16px;}
	.infos .nbr_topics .nbr {color: #999;}
	
	.comments {width: 100%; margin: 0 0 20px 0; color: #333; border-bottom: 1px solid #e9e9e9;}
	.solidborder {border-top: 1px solid #e9e9e9;}
	.dottedborder {border-top: 1px dotted #f5f5f5;}
	.comment.not_seen {background-color: #fee;}
	.comment {position: relative;}
	.comment .avatar {width: 30px; float: left; margin: 14px 0 0 18px;}
	.comment .right {width: 600px; float: left; margin: 14px 0 14px 14px;}
	.comment .author {margin: 1px 0 1px 0; font-weight: bold;}
	.comment .title {font-weight: bold; line-height: 1.28; margin: 5px 0 5px 0;}
	.comment .text {line-height: 1.28; margin: 5px 0 5px 0;}
	.comment .form {display: none; margin: 5px 0 5px 0;}
	.comment .footer {color: #999; line-height: 17px;}
	.comment .icon {margin-bottom: -3px; cursor: pointer;}
	.comment .icon2 {margin: 0 3px -2px 0;}
	
	.reply {width: 100%; margin: 20px 0 20px 0;}
	.reply .avatar {width: 30px; float: left; margin: 14px 0 0 18px;}
	.reply .textarea {float: left; width: 450px; margin-left: 14px;}
	.reply textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1; color: #333;}
	.reply .submit {float: left; margin-top: 19px;}
	
	.reply2 {width: 100%; margin: 5px 0 5px 0;}
	.reply2 .textarea {float: left; width: 450px;}
	.reply2 textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1;}
	.reply2 .submit {float: left; margin-top: 11px;}
	
	.comment .favon {font-weight: bold; font-style: italic; color: #333;}
	.comment .hidden {display: none;}
</style>

<?php
echo html::stylesheet('js/lib/markitup/skins/markitup/style.css', 'screen', false);
echo html::stylesheet('js/lib/markitup/sets/markdown/style.css', 'screen', false);
echo html::script('js/lib/markitup/jquery.markitup.js');
echo html::script('js/lib/markitup/sets/markdown/set.js');
require_once Kohana::find_file('libraries', 'markdown');
?>	

<h1>Forum</h1>

<div class="clearright"></div>

<div class="comments">
	<?php
	$last_user_id = 0;
	$dark = "";
	echo View::factory('topics/comment')
		->bind('user', $user)
		->bind('topic', $topic)
		->bind('comment', $topic->comments[0])
		->bind('last_user_id', $last_user_id)
		->bind('dark', $dark);
	?>
</div>

<div class="infos">
		
	<div class="pages">
		<b><?php echo $pagination->render() ?></b>
	</div>
	
	<div class="nbr_topics">
		<?php 
		echo ' <span class="nbr">' . $nbr_comments . '</span> ';
		echo Kohana::lang('topic.' . inflector::plural('comment', $nbr_comments));
		?>
	</div>
	
</div>
	
<div class="comments">
<?php
$tr = 0;
$last_user_id = 0;
$first_comment_id = $topic->comments[0]->id;
$last_dark = " error";
foreach ($comments as $comment) 
{
	
	$inc = true;
	$dark = ($tr % 2 == 0) ? ' dark' : "";
	if ($comment->user->has_role('modo')) {$dark = ' modo'; $inc = false;}
	if ($comment->user->has_role('admin')) {$dark = ' admin'; $inc = false;}
	if ($last_user_id == $comment->user_id) {$dark = $last_dark; $inc = false;}
	if ($inc) {
		$tr ++;
	}

echo View::factory('topics/comment')
	->bind('user', $user)
	->bind('topic', $topic)
	->bind('comment', $comment)
	->bind('last_user_id', $last_user_id)
	->bind('dark', $dark);

	$last_user_id = $comment->user_id;
	$last_dark = $dark;
}
?>
</div>

<?php if ( ($user->loaded and ($user->has_role('modo') or ! $topic->locked)) ) : ?>

	<?php echo form::open('comments/new', array(), array('topic_id' => $topic->id)) ?>
	<div class="reply">
		<div class="avatar">
		
		</div>
		<div class="textarea">
			<?php echo form::textarea(array(
	        	'class' => 'markdown', 
	        	'name' => 'content', 
	        	'placeholder' => 'Un commentaire ?',
	        	'value' => ''
	        )) ?>
	    </div>
		<div class="submit">
			<?php echo form::submit(array(
		    	'name' => 'submit', 
		    	'id' => 'submit', 
		    	'class' => 'button blue',
		    	'value' => 'Poster'
		    )) ?>
		</div>
	</div>
	<?php echo form::close() ?>	
	<div class="clearleft"></div>
	
<?php endif; ?>

<script>

$(function(){

	$('.markdown').markItUp(mySettings);

	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	// Afficher les +1/-1
	$('.comment').hover(function(){
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
		$('#comment' + comment_id).find('.text').toggle();
		$('#comment' + comment_id).find('.form').toggle();
		return false;
	});
	
	$('.submit').click(function(){
		var id = $(this).attr('id').substring(1);
		var content = $('#c' + id + ' textarea[name=content-edit]').val();
		$.post(baseUrl + 'comments/' + id + '/edit', {'content': content}, function(data){
			$('#c' + id + ' .form').hide();
			$('#c' + id + ' .text').show().html(data.text);
			$('#c' + id + ' .footer').show();
			$('#c' + id + ' .footer .date').html(data.date + ' (modification)');
		});
		return false;
	});
	
	$('.cancel').click(function(){
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