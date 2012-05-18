<style>
	.user {width: 100%;} 
	.user .left {width: 164px; float: left; margin: 5px 0 0 18px;}
	.user .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.user input[type=text], .user input[type=password] {width: 250px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<h1>Inscription</h1>

<?php echo form::open('admin/pnjs/new') ?>

<div id="errors"></div>

<div class="user">
	
	<div class="left">
		<?php echo form::label('name', 'Nom du PNJ') ?>
	</div>
	<div class="right">
		<?php echo form::input('name', '', 'autocomplete = off') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php
		echo form::submit(array(
			'name' => 'submit', 
			'id' => 'submit', 
			'class' => 'button blue',
			'value' => "Ajouter"
		));
		?>
	</div>
	<div class="clearleft"></div>
	
</div>

<?php echo form::close() ?>
