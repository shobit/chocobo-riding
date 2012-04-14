<h1><?php echo Kohana::lang('forum.title'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('forum.topic.edit.prelude'); ?></div>

<?php
echo form::open("forum/topic/edit/".$topic->id);

$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error.'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}
?>

<h2>Informations</h2>

<div class="leftPart2"><table>
	
	<tr>
		<td></td>
		<td class="label">
			<?php echo form::label('user_id', Kohana::lang('forum.topic.user')) ?>
		</td>
		<td class="value">
			<?php 
			echo '<b>'.gen::get_username($form['user_id']).'</b>';
			?>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td class="label">
			<?php echo form::label('title', Kohana::lang('forum.topic.title')) ?>
		</td>
		<td class="value">
			<?php echo form::input('title', $form['title']) ?>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td class="label"><?php echo form::label('type', Kohana::lang('forum.topic.type')) ?></td>
		<td class="value">
			<?php echo form::dropdown('type', $list_types, $form['type']) ?>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td class="label">
			<?php 
			echo form::label(array(
				'id'=>'lbl_shared',
				'for'=>'shared', 
				'value'=>Kohana::lang('forum.topic.share'),
				'title'=>Kohana::lang('forum.topic.lbl_share')
			)); 
			?>
		</td>
		<td class="value">
			<?php 
			echo form::checkbox('shared', '', $form['shared']).' '; 
			?>
		</td>
	</tr>
	
	<tr id="shared_users">
		<td></td>
		<td class="label"><?php echo form::label('users', Kohana::lang('forum.topic.users')) ?></td>
		<td class="value">
			<?php 
			$options = array();
			foreach($topic->flows as $flow) 
				if ($flow->user_id != $user->id)
					$options[$flow->user_id] = $flow->user->username;
			echo form::dropdown('users', $options);
			?>
		</td>
	</tr>
	
</table></div>

<div class="leftPart"><table>
	
	<?php if ($user->has_role('admin') and ! $topic->loaded): ?>
	<tr id="updateTr">
		<td></td>
		<td class="label">
			<?php echo form::label('update', Kohana::lang('forum.topic.update')); ?>
		</td>
		<td class="value">
			<?php echo form::checkbox('update', '', $form['update']).' '; ?>
		</td>
	</tr> 
	
	<tr id="tr_version" style="display:none;">
		<td></td>
		<td class="label">
			<?php
			$site = ORM::factory('site', 1);
			echo "<small>(".$site->version_number.")</small>";
			?>
		</td>
		<td class="value">
			<?php echo form::input(array('id'=>'version', 'name'=>'version', 'value'=>$form['version'], 'style'=>'width:70px;')); ?>
		</td>
	</tr>
	<?php endif; ?>
	
	<?php if ($user->has_role(array('admin', 'modo')) and $topic->loaded): ?>
	<tr>
		<td></td>
		<td class="label">
			<?php echo form::label('locked', Kohana::lang('forum.topic.lock')); ?>
		</td>
		<td class="value">
			<?php echo form::checkbox('locked', '', $form['locked']).' '; ?>
		</td>
	</tr> 
	
	<tr>
		<td></td>
		<td class="label">
			<?php echo form::label('archived', Kohana::lang('forum.topic.archive')); ?>
		</td>
		<td class="value">
			<?php echo form::checkbox('archived', '', $form['archived']).' ';	?>
		</td>
	</tr> 
	
	<tr>
		<td></td>
		<td class="label">
			<?php echo form::label('deleted', Kohana::lang('forum.topic.delete')); ?>
		</td>
		<td class="value">
			<?php echo form::checkbox('deleted', '', $form['deleted']).' '; ?>
		</td>
	</tr> 
	<?php endif; ?>
	
</table></div>

<div class="clearBoth"></div>

<h2>Message</h2>

<?php echo form::textarea(array(
	'name'=>'content', 
	'value'=>$form['content'],
	'id'=>'textile'
)) ?>

<p>
	<?php echo html::anchor("forum", html::image('images/buttons/back.gif')) ?>
	
	<?php echo form::submit('submit', 'Valider') ?>
</p>
	
<?php 
echo form::close();
?>


<script>
	$(document).ready(function(){
		var option = $("#type option:selected");	
		if (option.attr('value') != 'update'){
			$('#updateTr').hide();
		}
		
		if ($('#shared').is(':checked')){
			$('#locked, #archived').attr('disabled', true);
		}else{
			$('#shared_users').fadeOut();
		}
		
		if ($('#update').is(':checked')){
			$('#tr_version').show();
		}
		
		$('#type').change(function(){
			option = $("#type option:selected");
			if (option.attr('value') == 'update'){
				$('#updateTr').fadeIn();
			}else{
				$('#updateTr').fadeOut();
			}
		});
		
		$('#shared').click(function(){
			if ($('#shared').is(':checked')){
				$('#shared_users').fadeIn();
				$('#locked, #archived').attr('disabled', true);
				$('#locked, #archived').attr('checked', false);
			}else{
				$('#shared_users').fadeOut();
				$('#locked, #archived').removeAttr('disabled');
			}
		});
		
		$('#archived').click(function(){
			if ($('#archived').is(':checked')){
				$('#locked').attr('disabled', true);
				$('#locked').attr('checked', true);
			}else{
				$('#locked').removeAttr('disabled');
				$('#locked').attr('checked', false);
			}
		});
		
		$('#deleted').click(function(){
			if ($('#deleted').is(':checked')){
				$('#locked, #archived').attr('disabled', true);
				$('#locked, #archived').attr('checked', false);
			}else if ( ! $('#shared').is(':checked')){
				$('#locked, #archived').attr('disabled', false);
			}
		});
		
		$('#update').click(function(){
			if ($('#update').is(':checked')){
				$('#tr_version').fadeIn();
			}else{
				$('#tr_version').fadeOut();
			}
		});
		
		$("#users").fcbkcomplete({
			json_url: "../../../topic/autocompletion",
			cache: true,
			filter_hide: true,
			newel: true
		});

		$('label[for=lbl_shared]').tipsy({gravity:'e'});
	});
</script>