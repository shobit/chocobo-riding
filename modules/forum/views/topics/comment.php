<?php
$first_comment = ($topic->comments[0]->id == $comment->id);
$different_user = ($last_user_id !== $comment->user_id);
$border = ($different_user) ? ' solidborder': ' dottedborder';

// Notification
$not_seen = '';
$comment_notification = $comment->get_notification($user->id);
if ($comment_notification->loaded) 
{
	$comment_notification->delete();
	$not_seen = ' not_seen';
}			

?>

<div class="comment<?php echo $border . $not_seen ?>" id="comment<?php echo $comment->id ?>">
	<div class="options">
		<?php
		if ($first_comment and $topic->allow($user, 'w'))
		{
			echo html::anchor('topics/' . $topic->id . '/edit', 
				html::image('images/icons/edit.png', array('class' => 'icon', 'title' => 'Modifier', 'rel' => 'tipsy'))) . ' | ';
			echo html::anchor('#', 
				html::image('images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), 
					array('class' => 'delete_topic', 'id'=>'topic' . $topic->id)); 		
		}
		else if ( ! $first_comment and $comment->user_id == $user->id)
		{
			echo html::anchor('#', html::image('images/icons/edit.png', array('class' => 'icon', 'title' => 'Modifier', 'rel' => 'tipsy')), array('class' => 'edit', 'id' => 'edit' . $comment->id));
		}
		?>
	</div>
	<div class="avatar">
		<?php
		if ($different_user) 
		{
			echo $comment->user->image('mini');
		}
    	?>
	</div>
	<div class="right<?php echo $dark ?>" id="c<?php echo $comment->id ?>">
		<div class="author">
			<?php 
			if ($different_user)
			{
				echo $comment->user->link();
			}
			?>
        </div>
		
		<div class="title">
			<?php
			if ($first_comment)
			{
				echo $topic->title;
				echo $topic->display_view_tags();
			}
			?>
		</div>
		
		<div class="text">
			<?php echo Markdown($comment->content) ?>
		</div>
		
		<?php if ( ! $first_comment and $comment->user_id == $user->id): ?>
			<div class="form">
				<div class="reply2">
				<?php echo form::open('comments/' . $comment->id . '/edit') ?>
				<div class="textarea">
					<?php echo form::textarea(array(
			        	'id' => 'content-edit',
			        	'class' => 'markdown', 
			        	'name' => 'content-edit', 
			        	'value' => $comment->content
			        )) ?>
			   </div>
				<div class="submit">
					<div class="button blue submit" id="c<?php echo $comment->id ?>">Modifier</div><br />
					<div class="button grey cancel" id="c<?php echo $comment->id ?>">Annuler</div>
				</div>
				<?php echo form::close() ?>
				</div>
				<div class="clearleft"></div>
			</div>
		<?php endif; ?>
		
    	<div class="footer">	
			<?php
			
			echo '<span class="date">' . date::display(max($comment->created, $comment->updated)) . '</span>';
			
			if ($comment->updated > $comment->created) 
			{
				echo ' (modification)';
			}
			
			$nb_interests = $this->db
				->where('comment_id', $comment->id)
				->count_records('comments_favorites');
			
			$hidden = ($nb_interests == 0) ? 'hidden' : '';
			$favtext = ($user->has(ORM::factory('c_favorite', $comment->id))) ? 'new': 'empty';
			
			if ($user->loaded or $nb_interests > 0)
			{
				echo '<span id="nbfavs' . $comment->id . 'w" class="nbfavsw '.$hidden.'">';
				echo ' &nbsp; <span id="nbfavs' . $comment->id . '" class="nbfavs favon">+' . $nb_interests . '</span>';
		      	echo '</span>';
			}
				      	
	      	if ($user->loaded)
	      	{
		      	echo '<span class="favw" style="display: none;">';
		      	echo ' &nbsp; ' . html::image('images/forum/star-' . $favtext . '.png', array('id' => 'fav' . $comment->id, 'class' => 'fav icon'));
				echo '</span>';
			}
				      	
			/*if ( $comment->topic->allow($user, 'w') ) 
			{
				echo ' · ' . html::anchor('#', 'éditer');
				echo ' · ' . html::anchor('#', 'supprimer');
			}*/
			?>
		</div>
    </div>
    <div class="clearleft"></div>
</div>