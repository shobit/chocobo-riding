<h1><?php 
switch($type) {
	case 'password'		: echo Kohana::lang('user.lost.title1'); break;
	case 'activation'	: echo Kohana::lang('user.lost.title2'); break;
}
?></h1>
<div id="prelude"><?php 
switch($type) {
	case 'password'		: echo Kohana::lang('user.lost.prelude1'); break;
	case 'activation'	: echo Kohana::lang('user.lost.prelude2'); break;
}
?></div>

<?= form::open('user/lost/'.$type); ?>

<?php 
$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error.'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}
?>
	
<div class="leftPart2">
	
	<p><table>	
		<tr>
			<td><?= html::image('images/theme/compte.gif') ?></td>
			<td class="label"><?= Kohana::lang('user.form.username') ?></td>
			<td><?= form::input('username', $form['username']) ?></td>
		</tr>
		
		<tr>
			<td><?= html::image('images/menu/poste.gif'); ?></td>
			<td class="label"><?= Kohana::lang('user.form.email') ?></td>
			<td><?= form::input('email', $form['email']) ?></td>
		</tr>
	</table></p>
	
</div>

<div class="leftPart">

	<center>
			<p><?= html::image('images/theme/mog.png') ?></p><br /> 
			
			<p><?= form::submit('submit', 'Valider') ?></p> 
			
			<p><?= html::anchor('home', html::image('images/buttons/back.gif')) ?></p> 
	</center>

</div>

<?php 
echo form::close();
?>
