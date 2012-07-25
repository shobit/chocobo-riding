<h1>Ajouter une mise à jour</h1>

<?php echo form::open() ?>

<div class="field">
	<div class="label"><?php echo form::label('type', 'Type*') ?></div>
	<div><?php echo form::input('type', $form['type']) ?></div>
	<div class="clearleft"></div>
</div>

<div class="field">
	<div class="label"><?php echo form::label('title', 'Principal*') ?></div>
	<div><?php echo form::input('title', $form['title']) ?></div>
	<div class="clearleft"></div>
</div>

<div class="field">
	<div class="label"><?php echo form::label('content', 'Secondaire') ?></div>
	<div><?php echo form::textarea('content', $form['content']) ?></div>
	<div class="clearleft"></div>
</div>

<div class="field">
	<div class="label"><?php echo form::label('date', 'Date') ?></div>
	<div><?php echo form::input('date', $form['date']) ?></div>
	<div class="clearleft"></div>
</div>

<?php echo form::submit(NULL, 'Valider') ?>

<?php echo form::close() ?>

<script type="text/javascript">

$(document).ready(function(){
	
	// FancyBox
	var id = <?php echo $form['id'] ?>;
	
	$('form').bind('submit', function(){
		
		$.fancybox.showLoading();
		
		$.ajax({
			url: baseUrl + 'update/ajax_edit/' + id,
			type: 'POST',
			data: $(this).serializeArray(),
			dataType: 'json',
			success: function(data){
				$.fancybox.hideLoading();
				if (data.success){
					$.fancybox.close();
				}
			}
		});
		
		return false;
	});
	
});

</script>