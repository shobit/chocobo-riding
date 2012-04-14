<h1><?= Kohana::lang('forum.title') ?></h1>
<div id="prelude"><?= Kohana::lang('forum.post.prelude') ?></div>

<?= form::open('forum/comment/edit/'.$comment->id) ?>

<div class="leftPart2">
	
	<p><table width="100%">
		<tr>
			<td></td>
			<td class="label"><?= Kohana::lang('forum.topic.user') ?></td>
			<td class="value">
				<b><?= gen::get_username($form['user_id']) ?></b>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td class="label"><?= 
				form::label('content', Kohana::lang('forum.post.content')) ?></td>
			<td class="value">
				<?= form::textarea(array(
					'id'=>'textile',
					'name'=>'content', 
					'value'=>$form['content']
				)) ?>
			</td>
		</tr>
		
	</table></p>
	
</div>

<div class="leftPart">

	<center>
	<p><?= form::submit('submit', 'Valider') ?></p>
	<p><?= html::anchor($comment->topic->get_url_last_comment(), 
		html::image('images/buttons/back.gif')); ?></p>
	</center>
	
</div>

<div class="clearBoth"></div>
	
<?php 
echo form::close();
?>
