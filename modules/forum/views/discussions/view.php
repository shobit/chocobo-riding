<style>
	.infos {margin: 20px 0 0 0; border-bottom: 1px solid #ddd;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_discussions {font-size: 16px;}
	.infos .nbr_discussions .nbr {color: #999;}
	
	.messages {width: 100%; margin: 0 0 20px 0; color: #333;}
	.message {border-bottom: 1px solid #e9e9e9;}
	.message .avatar {width: 64px; float: left; margin: 14px 0 0 18px;}
	.message .right {width: 650px; float: left; margin: 14px 0 14px 14px;}
	.message .author {margin: 1px 0 1px 0; font-weight: bold;}
	.message .notifications {line-height: 1.28; margin: 5px 0 5px 0; color: #900;}
	.message .text {line-height: 1.28; margin: 5px 0 5px 0;}
	.message .footer {color: #999; line-height: 17px;}
	.message .icon {margin-bottom: -3px; cursor: pointer;}
	.message .icon2 {margin: 0 3px -2px 0;}
	
	.reply {width: 100%; margin: 20px 0 20px 0;}
	.reply .avatar {width: 64px; float: left; margin: 14px 0 0 18px;}
	.reply .textarea {float: left; width: 450px; margin-left: 14px;}
	.reply textarea {width: 450px; height: 150px; outline: none; resize: none; padding: 3px; border-color: #899BC1;}
	.reply .submit {float: left;}
	
	.message .favon {font-weight: bold; font-style: italic; color: #333;}
	.message .hidden {display: none;}
</style>

<h1>Messages</h1>

<div class="infos">
	
	<div class="pages">
		<b><?php echo $pagination->render() ?></b>
	</div>
	
	<div class="nbr_discussions">
		<?php 
		echo 'Conversation avec ' . $receiver->name . ' ~ ';
		echo ' <span class="nbr">' . count($messages) . '</span> ';
		echo Kohana::lang('discussion.' . inflector::plural('message', count($messages)));
		?>
	</div>
	
</div>

<div class="clearright"></div>
	
<div class="messages">
<?php
$tr = 0;
$last_user_id = 0;
$first_message_id = $discussion->messages[0]->id;
$last_dark = " error";
foreach ($messages as $message) 
{
	$inc = true;
	$dark = ($tr % 2 == 0) ? ' dark' : "";
	if ($message->user->has_role('modo')) {$dark = ' modo'; $inc = false;}
	if ($message->user->has_role('admin')) {$dark = ' admin'; $inc = false;}
	if ($last_user_id == $message->user_id) {$dark = $last_dark; $inc = false;}
	if ($inc) {
		$tr ++;
	}
	?>
<div id="m<?php echo $message->id ?>"></div>
<div class="message">
	<div class="avatar">
		<?php
		if ($last_user_id !== $message->user_id) 
		{
			echo $message->user->image('mini');
		}
    	?>
	</div>
	<div class="right<?php echo $dark ?>" id="c<?php echo $message->id ?>">
		<div class="author">
			<?php echo $message->user->link() ?>
        </div>
		
		<div class="notifications">
			<?php
			if ($user->has(ORM::factory('m_notification', $message->id))) 
			{
				$user->remove(ORM::factory('m_notification', $message->id));
				$user->save();
				echo html::image('images/forum/post.png', array('class' => 'icon2'));
				echo 'Message non lu';
			}
			?>
		</div>
		
		<div class="text">
			<?php 
			//$textile = new Textile;
			//$content = $textile->TextileThis($message->content);
			echo nl2br($message->content);
			?>
		</div>
    	
    	<div class="footer">	
			<?php echo date::display($message->created) ?>
		</div>
    </div>
	<div class="clearleft"></div>
</div>
	
	<?php
	$last_user_id = $message->user_id;
	$last_dark = $dark;
}
?>
</div>

<?php if ($user->loaded) : ?>

	<?php echo form::open('message/add', array(), array('discussion_id' => $discussion->id)) ?>
	<div class="reply">
		<div class="avatar">
		
		</div>
		<div class="textarea">
			<?php echo form::textarea(array(
	        	'id' => 'textile', 
	        	'name' => 'content', 
	        	'value' => ''
	        )) ?>
		</div>
		<div class="submit">
			<?php echo form::submit(array(
		    	'name' => 'submit', 
		    	'id' => 'submit', 
		    	'value' => 'Envoyer'
		    )) ?>
		</div>
	</div>
	<?php echo form::close() ?>	
	<div class="clearleft"></div>
	
<?php endif; ?>
