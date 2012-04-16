<?php

$user = $this->session->get('user');
if ($user->id >0) {
	$user->reload();
	$chocobo_used = $this->session->get('chocobo');
	
	foreach ($user->chocobos as $chocobo) {
		?><div class="menu_chocobo">
		
			<div class="menu_image">
				<?= $chocobo->display_image("menu") ?>
			</div>
			
			<?php
			$tmp = (strcmp($chocobo->id, $chocobo_used->id) == 0) ? 1 : 2;
			$selected = (strcmp($chocobo->id, $chocobo_used->id) == 0) ? 
				"menuSelected" : "menuNotSelected";
			?>
			
			<div class="menu_texte<?= $tmp ?>"> 
				<b><?= html::anchor('chocobo/change/'.$chocobo->name, 
					$chocobo->name, array('class' => $selected)) ?></b>, 
				<small>nv<?= $chocobo->level ?><br />
				<?php 
					if ($chocobo->status == 2) echo "Bébé";
					else echo $chocobo->display_job('zone'); ?><br />
				<?php
				if (strcmp($chocobo->id, $chocobo_used->id) == 0) { ?>
					<span class="line_rouge"><?= floor($chocobo->pl) ?></span>/
					<span class="line_vert"><?= floor($chocobo->hp) ?></span>/
					<span class="line_bleu"><?= floor($chocobo->mp) ?></span>/
					<span class="line_gris"><?= $chocobo->rage ?></span><?php
				} else {
					echo floor($chocobo->pl).'/ '.floor($chocobo->hp).'/ '.
						floor($chocobo->mp).'/ '.$chocobo->rage;
				}
				?>
				</small>
			</div>
		
			<div class="clearBoth"></div>
		
		</div><?php
	}
		
} else {
	echo form::open('user/login', array('class'=>'frm_connexion'));
    
	echo form::label('username', Kohana::lang('menu.pseudo')).'<br />';
	echo form::input('username').'<br /><br />'; 
	
	echo form::label('password', Kohana::lang('menu.password')).'<br />';
	echo form::password('password').'<br /><br />'; 
	
	echo form::checkbox('remember', '', false); 
	echo form::label('remember', Kohana::lang('menu.auto')).'<br /><br />';
	
	echo form::submit('submit', Kohana::lang('menu.connect')); 
	echo form::close(); 
	?>
	
	<br />
    <small>
        <?php echo html::anchor('register', Kohana::lang('menu.subscribe')); ?><br />
        <?php echo html::anchor('user/lost/password', Kohana::lang('menu.lost_mdp')); ?><br />
        <?php echo html::anchor('user/lost/activation', Kohana::lang('menu.lost_link')); ?>
    </small>
    
	<?php
}

?>