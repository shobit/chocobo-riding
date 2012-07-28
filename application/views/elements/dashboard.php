<?php

$user = $this->session->get('user');
$chocobo = $this->session->get('chocobo');

if ($user->loaded): ?>

	<div class="item">
		<?php 
		echo $user->image('mini', array('style' => 'margin: 0 5px -8px 0;'));
		echo $user->link(); 
		?>
	</div>
	
	<div class="item">
		<?php 
		echo $chocobo->image('mini', array('style' => 'margin: 0 5px -5px 0;'));
		echo $chocobo->link(); 
		?>
	</div>
	
	<div class="clearright"></div>

<?php endif; ?>
