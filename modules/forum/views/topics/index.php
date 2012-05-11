<h1><?php echo Kohana::lang('forum.title') ?></h1>
<div id="prelude"><?php echo Kohana::lang('forum.prelude') ?></div>

<div class="pannel">
	<?php echo View::factory('common/panel', array('user' => $user, 'type' => $type, 'nbr_topics' => $nbr_topics, 'pagination' => $pagination)) ?>
</div>

<p>
<table class="forum">
	<?php
		foreach ($topics as $n => $t) 
    	{
    		$topic = ORM::factory('topic', $t->id);
    		$nbr_comments = count($topic->comments);
            $last_comment = $topic->comments[$nbr_comments-1];
            $url = url::base().$topic->get_url_last_comment();
            $notif = ORM::factory('notif')
            	->where('topic_id', $topic->id)
            	->where('user_id', $user->id)
            	->find();
            $notified = ($notif->loaded);
		?>
	<tr link="<?php echo $url; ?>"<?php if ($notified) echo ' class="notified"'; ?>>
		<td class="icon" id="td_icon<?php echo $topic->id; ?>">
    		<?php
    		$icon = (count($topic->favorites)==0) ? "empty": "new";
    		echo html::anchor(
    			'topic/favorite/'.$topic->id, 
    			html::image("images/forum/star-$icon.png", array('id' => 'img_icon'.$topic->id))
    		);
			?>
    	</td>
    	<td class="username">
    		<?php 
    		echo $last_comment->user->username;
        	if ($nbr_comments>1) echo ' <small class="number">('.($nbr_comments-1).')</small>';
        	?>
    	</td>
    	<td class="arrow">› </td>
		<td class="title">
			<a href="<?php echo $url ?>">
			<div class="overflow" style="width:510px;">
				<span class="title"><?php echo $topic->title; ?></span> 
				<?php if ($topic->locked) echo html::image('images/forum/lock.png', array('style'=>'margin-bottom:-2px;')); ?> - 
				<span class="content">
					<?php 
					echo $last_comment->content;
					?>
				</span>
			</div>
			<?php if (false)://if ($topic->allow($user, 'w')): ?>
			<div class="actions" style="display:none;position:relative;right:0;top:0;">
				<?php 
				echo html::anchor(
					'forum/topic/edit/'.$topic->id, 
					html::image('images/forum/edit.gif')
				).' ';
			
				echo html::anchor(
					'forum/topic/delete/'.$topic->id, 
					html::image('images/forum/delete.gif'), 
					array('title'=>'Supprimer')
				).' ';
				?>
			</div>
			<?php endif; ?>
			</a>
		</td>
		<td class="type"><?php echo html::image('images/forum/types/'.$topic->type.'.png'); ?></td>
		<td class="date"><?php echo date::display($last_comment->created); ?></td>
	</tr>
	<?php
    }
    ?>

</table>
</p>

<div class="pannel">
	<?php echo View::factory('common/panel', array('user' => $user, 'type' => $type, 'nbr_topics' => $nbr_topics, 'pagination' => $pagination)) ?>
</div>

<script>
$(document).ready(function(){

	$('.icon').click(function(){
		var id = $(this).attr('id').substring(7);
		$.ajax({
			type: "POST",
			url: baseUrl+"topic/favorite/"+id,
			dataType: "json",
			success: function(data){
				$('#img_icon'+id).attr('src', baseUrl+data.image);
			}
		});
		return false;
	});

	$('.number').hide('fast');
	$('tr').hover(function(){
		//$(this).find('.number').show();
		$(this).find('.actions').show();
	}, function(){
		//$(this).find('.number').hide();
		$(this).find('.actions').hide();
	});
	
	$('#username, #title, #date, #type').click(function(){
		window.location = $(this).parent().attr('link');
		return false;
	});
	
	// BUTTON category
	
	$('.button-category').click(function(){
		$(this).css('background-color', '#eee');
		$(this).next()
			.show()
			.mouseleave(function(){
				$(this).hide();
				$('.button-category').css('background-color', '#fff');
			});
	});
	
	$('.action-category').click(function(){
		window.location = baseUrl+'forum/'+$(this).attr('name');
	});
	
	
});
</script>
