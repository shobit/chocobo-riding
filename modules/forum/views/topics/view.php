<?php

$nbr_comments = count($topic->comments);

echo '<h1>'.Kohana::lang('forum.title').'</h1>';
echo '<div id="prelude">'.Kohana::lang('forum.prelude').'</div>';

if ($topic->shared) 
{
	echo '<p><b>'.Kohana::lang('letter.participants').'</b>';
	$res = array();
	foreach ($topic->flows as $flow) $res[] = $flow->user->username;
	echo implode(', ', $res).'.</p>';
}

?>
<div>
	<div style="width: 500px; float:left">
		<?php echo html::anchor('forum', 'Forum') ?> »
		<?php echo html::image('images/forum/types/'.$topic->type.'.png') ?>
		<?php 
			$icon = "";
			if ($topic->locked) $icon .= ' '.html::image('images/theme/cadenas.gif');
			echo $icon;
		?>
    	<b><?php echo $topic->title ?></b> 
    	<small>(<?php echo $nbr_comments.' '.Kohana::lang('forum.comments'); ?>)</small>
    	 - <?php echo html::anchor('#content', 'Répondre', array('class'=>"button")); ?>
	</div>	
	
	<div class="paginator">
		<?php
		echo html::image('images/forum/pages.png');
		echo $pagination->render();
		?>
	</div>
</div>
<?php

if (count($nbr_comments) == 0) {
	?><div class="msgInfo"><?php echo Kohana::lang('forum.post.no_posts') ?></div><?php
} else {
	?>
	<p>
	<table class="forum2">
    <?php
    $tr = 0;
    $last_user_id = 0;
    $first_comment_id = $topic->comments[0]->id;
    $last_dark = " error";
    foreach ($comments as $comment) {
        $inc = true;
        $dark = ($tr % 2 == 0) ? ' dark' : "";
        if ($comment->user->has_role('modo')) {$dark = ' modo'; $inc = false;}
        if ($comment->user->has_role('admin')) {$dark = ' admin'; $inc = false;}
        if ($last_user_id == $comment->user_id) {$dark = $last_dark; $inc = false;}
        if ($inc) $tr += 1;
        ?>
        <tr>
        	<td class="left">
        		<?php
	            if ($last_user_id != $comment->user_id) {
	                echo $comment->user->display_image('thumbmail');?><br />
	                <b><?php echo html::anchor('user/view/'.$comment->user->username, $comment->user->username) ?></b>
	            	<?php if ($comment->user->is_connected()) echo " ".html::image('images/icons/online.png'); ?><br />
	                <?php echo $comment->user->role();
	            }
            	?>
        	</td>
        	<td class="right<?php echo $dark ?>" id="comment<?php echo $comment->id ?>">
        		<div class="signature">
        		
        			<div style="float:right;" class="interests">
	        				<?php
		        			if ($user->has_role(array('admin', 'modo')) and $topic->shared) 
		        			{
		        				$interest = ORM::factory('interest')
			        				->where('user_id', $user->id)
			        				->where('comment_id', $comment->id)
			        				->find();
			        			
			        			echo "Intérêt: ";
			        			$bold = ($interest->value == -1) ? " red" : "";
			        			echo '<span class="minus '.$bold.'">';
			  		      		echo html::anchor('comment/interest/'.$comment->id.'/-1', "-");
			  		      		echo '</span> ';
			  		      		
			  		      		$bold = ($interest->value == 1) ? " green" : "";
			  		      		echo '<span class="plus '.$bold.'">';
			  		      		echo html::anchor('comment/interest/'.$comment->id.'/1', "+");
			  		      		echo '</span> ';
			  		      		
			  		      		$somme = 0;
			        			foreach ($comment->interests as $interest)
			        				$somme += $interest->value;
			        			$class = "";
			        			if ($somme < 0) $class = " red";
			        			if ($somme > 0) $class = " green";
			        			echo '<span class="somme'.$class.'">';
			        			if ($somme > 0) echo "+";
			        			echo $somme;
			        			echo "</span> ";
			        			
			        			$class = ($interest->value) ? " grey" : " none";
			  		      		echo '<span class="delete '.$class.'">';
			        			echo html::anchor('comment/interest/'.$comment->id.'/0', "x");
			  		      		echo '</span>';
		  		      		
		  		      		}
		  		      		else
		  		      		{
		  		      			$somme = 0;
			        			foreach ($comment->interests as $interest)
			        				$somme += $interest->value;
			        			if ($somme != 0)
			        			{
				        			echo "Intérêt: ";
				        			$class = "";
				        			if ($somme < 0) $class = " red";
				        			if ($somme > 0) $class = " green";
				        			echo '<span class="somme'.$class.'">';
				        			if ($somme > 0) echo "+";
				        			echo $somme;
				        			echo "</span>";
		  		      			}
		  		      		}
		        			?>
	        		</div>
        		
            		<?php 
	            	echo html::anchor(
	            		'topic/view/'.$topic->id.'#comment'.$comment->id, 
	            		'#', 
	                	array('name'=>'comment'.$comment->id)
	                ).' ';
	                
	                ?>Posté: <?php $tl = gen::time_left($comment->created); echo $tl['short'];
	                
	            	if ( $comment->topic->allow($user, 'w') ) 
	            	{
	                	if ($comment->is_first())
	                	{
	                		echo ' ['.html::anchor('forum/topic/edit/'.$comment->topic->id, 'Editer').']'; 
	            		}
	            		else
	            		{
	            			echo ' ['.html::anchor('forum/comment/edit/'.$comment->id, 'Editer').']'; 
	            		}
	            	}
			            	
	            	if (!empty($comment->updated))
	            	{
	            		?><br />&nbsp;&nbsp; Modifié: 
	            		<?php $tl = gen::time_left($comment->updated); echo $tl['short'];
	            	}
	            	?>
            	</div>
        		<?php
        		$textile = new Textile;
				$content = $textile->TextileThis($comment->content);
        		echo $content;
            	?>
            </td>
        </tr>
        <?php
        $last_user_id = $comment->user_id;
        $last_dark = $dark;
    }
    ?>
	</table>
	</p><br />
<?php
}

if ( ($user->loaded and ($user->has_role('modo') or ! $topic->locked)) ) 
{ ?>

<h2>Répondre</h2>

<?php echo form::open("forum/topic/".$topic->id) ?>

<div class="reply" name="reply">
    <p>
        <?php echo form::textarea(array(
        	'id'=>'textile', 
        	'name'=>'content', 
        	'value'=>$form['content'],
        	'cols'=>'100',
        	'rows'=>'12'
        )) ?>
    </p>

    <?php echo form::submit(array(
    	'name'=>'submit', 
    	'id'=>'submit', 
    	'value'=>'Envoyer', 
    	'style'=>'float:right; margin-right:25px;', 
    	'class'=>'button'
    )); ?>
    <div class="clearBoth"></div>
</div>

<?php 
form::close();
} ?>