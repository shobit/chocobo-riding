<style>
	.infos {margin: 20px 0 0 0; border-bottom: 1px solid #ddd;}
	.infos .pages {color: #999; font-weight: bold; float: right; font-size: 16px;}
	.infos .nbr_discussions {font-size: 16px;}
	.infos .nbr_discussions .nbr {color: #999;}
	
	.discussions {width: 100%; margin: 0 0 20px 0;}
	.discussion {border-bottom: 1px solid #ddd;}
	.discussion:hover {background-color: #f5f5f5;}
	.discussion .left {width: 30px; float: left; margin: 14px 0 0 18px;}
	.discussion .right {width: 600px; float: left; margin: 14px 0 14px 14px;}
	.discussion .author {margin: 1px 0 1px 0;}
	.discussion .title {line-height: 1.28; margin: 5px 0 5px 0;}
	.discussion .footer {color: #999; line-height: 17px;}
	.discussion .date {float: left;}
	.discussion .icon {margin-bottom: -3px; cursor: pointer;}
	.discussion .icon2 {margin: 0 3px -2px 0;}
	
	.discussion .favon {font-weight: bold; font-style: italic; color: #333;}
	.discussion .hidden {display: none;}
</style>

<h1>Messages</h1>

<div class="infos">
	
	<div class="pages">
		<b><?php echo $pagination->render() ?></b>
	</div>
	
	<div class="nbr_discussions">
		<?php 
		echo ' <span class="nbr">' . count($discussions) . '</span> ';
		echo Kohana::lang('discussion.' . inflector::plural('discussion', count($discussions)));
		?>
	</div>
	
</div>

<div class="clearright"></div>

<div class="discussions">
	
	<?php
	foreach ($discussions as $n => $discussion_id) 
	{
		$discussion = ORM::factory('discussion', $discussion_id);
		$receiver = $discussion->receiver($user->id);
	    $nbr_messages = count($discussion->messages);
		$last_message = $discussion->messages[$nbr_messages - 1];
		$notified = false;
	?>
		
	<div class="discussion">
		<div class="left">
			<?php echo $receiver->image('mini') ?>
    	</div>
    	<div class="right">
    		<div class="author">
    			<?php
    			$url = $last_message->url();
    			if ($last_message->user_id != $receiver->id)
    			{
    				echo 'Vous avez envoyé ' . html::anchor($url, 'un message') . ' à ' . $receiver->link();
    			}
    			else
    			{
    				echo $receiver->link() . ' vous a envoyé ' . html::anchor($url, 'un message') . ' : ';
    			}
    			?>
    		</div>
    		
    		<div class="title">
	    		<?php 
	    		$notifications = $discussion->get_notifications($user->id);
				$nbr_notifications = count($notifications);
				$new = ($nbr_notifications > 0) ? 'notif_new': 'notif_nonew';
				$nbr_messages = ($nbr_notifications > 0) ? $nbr_notifications: $nbr_messages;
				
				if ($nbr_messages > 0) 
				{
					echo '<span class="notif '.$new.'">' . $nbr_messages . '</span> ';
				}
				
	    		if ($last_message->user_id != $receiver->id)
    			{
    				echo html::image('images/forum/receiver.png') . ' ';
	    		}
	    		
	    		echo html::anchor('discussions/' . $discussion->id, $last_message->content, array('class' => 'discussiontitle'));
	    		?>
    		</div>
    		
    		<div class="footer">
	    		<?php echo date::display($last_message->created) ?>
			</div>
    	</div>
		<div class="clearleft"></div>
	</div>
		
	<?php
	}
	?>

</div>

<?php echo html::anchor('discussions/new', 'Envoyer un nouveau message', array('class' => 'button blue')) ?>
