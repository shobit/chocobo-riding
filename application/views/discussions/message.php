<?php
$messages = $discussion->messages->find_all();
$first_message = ($messages[0]->id == $message->id);
$different_user = ($last_user_id !== $message->user_id);
$border = ($different_user) ? ' solidborder': ' dottedborder';

// Notification
$not_seen = '';
if ($user)
{
	$not_seen = $message->delete_notification($user);
}
?>

<div class="message<?php echo $border.$not_seen ?>" id="message<?php echo $message->id ?>">
	<div class="options">
		<?php
		if ($first_message AND $discussion->allow($user, 'w'))
		{
			echo HTML::anchor('discussions/' . $discussion->id . '/edit', 
				HTML::image('media/images/icons/edit.png', array('class' => 'icon', 'title' => 'Modifier', 'rel' => 'tipsy'))) . ' | ';
			echo HTML::anchor('#', 
				HTML::image('media/images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), 
					array('class' => 'delete_discussion', 'id'=>'discussion' . $discussion->id)); 		
		}
		else if ( ! $first_message AND $user AND $message->user_id == $user->id)
		{
			echo HTML::anchor('#', HTML::image('media/images/icons/edit.png', array('class' => 'icon', 'title' => 'Modifier', 'rel' => 'tipsy')), array('class' => 'edit', 'id' => 'edit' . $message->id));
		}
		?>
	</div>
	<div class="avatar">
		<?php
		if ($different_user) 
		{
			echo $message->user->image('mini');
		}
    	?>
	</div>
	<div class="right<?php echo $dark ?>" id="c<?php echo $message->id ?>">
		<div class="author">
			<?php 
			if ($different_user)
			{
				echo $message->user->link();
			}
			?>
        </div>
		
		<div class="title">
			<?php
			if ($first_message)
			{
				echo $discussion->title;
			}
			?>
		</div>
		
		<div class="text">
			<?php echo Markdown::instance()->transform($message->content) ?>
		</div>
		
		<?php if ( ! $first_message AND $user AND $message->user_id == $user->id): ?>
			<div class="form">
				<div class="reply2">
				<?php echo Form::open('messages/'.$message->id.'/edit') ?>
				<div class="textarea">
					<?php echo Form::textarea('content-edit', $message->content, array(
			        	'id' => 'content-edit',
			        	'class' => 'markdown', 
			        )) ?>
			   </div>
				<div class="submit">
					<?php echo HTML::anchor('', __('Modifier'), array('id' => 'c'.$message->id, 'class' => 'button blue edit_msg')) ?><br />
					<?php echo HTML::anchor('', __('Annuler'), array('id' => 'c'.$message->id, 'class' => 'button grey cancel_msg')) ?>
				</div>
				<?php echo Form::close() ?>
				</div>
				<div class="clearleft"></div>
			</div>
		<?php endif; ?>
		
    	<div class="footer">	
			
			<span class="date">
				<?php
				echo Date::display(max($message->created, $message->updated));
				
				if ($message->updated > $message->created) 
				{
					echo ' (modification)';
				}
				?>
			</span>

			<?php
			/*$nb_interests = $this->db
				->where('message_id', '=', $message->id)
				->count_records('messages_favorites');
			
			$hidden = ($nb_interests == 0) ? 'hidden' : '';
			$favtext = ($user->has(ORM::factory('c_favorite', $message->id))) ? 'new': 'empty';
			
			if ($user->loaded or $nb_interests > 0)
			{
				echo '<span id="nbfavs' . $message->id . 'w" class="nbfavsw '.$hidden.'">';
				echo ' &nbsp; <span id="nbfavs' . $message->id . '" class="nbfavs favon">+' . $nb_interests . '</span>';
		      	echo '</span>';
			}
				      	
	      	if ($user->loaded)
	      	{
		      	echo '<span class="favw" style="display: none;">';
		      	echo ' &nbsp; ' . html::image('images/forum/star-' . $favtext . '.png', array('id' => 'fav' . $message->id, 'class' => 'fav icon'));
				echo '</span>';
			}*/
			?>
		</div>
    </div>
    <div class="clearleft"></div>
</div>