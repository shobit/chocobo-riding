<h1><?= Kohana::lang('user.register.title') ?></h1>
<div id="prelude"><?= Kohana::lang('user.register.prelude') ?></div>

<?= form::open('user/register'); ?>

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
			<td><?= html::image('images/theme/cadenas.gif'); ?></td>
			<td class="label"><?= Kohana::lang('user.form.password') ?></td>
			<td><?= form::password('password', $form['password']) ?></td>
		</tr>
			
		<tr>
			<td><?= html::image('images/theme/cadenas.gif'); ?></td>
			<td class="label"><?= Kohana::lang('user.form.password_again') ?></td>
			<td><?= form::password('password_again', $form['password_again']) ?></td>
		</tr>
		
		<tr>
			<td><?= html::image('images/menu/poste.gif'); ?></td>
			<td class="label"><?= Kohana::lang('user.form.email') ?></td>
			<td><?= form::input('email', $form['email']) ?></td>
		</tr>
	</table></p>
	
	<!--<span class="msgInformation">Les inscriptions ne vont pas tarder à s'ouvrir..</span>-->
	
</div>

<div class="leftPart">

	<center>
			<p><?= html::image('images/theme/mog2.jpg') ?></p><br /> 
			
			<p><?= form::submit('submit', 'Valider') ?></p> 
			
			<p><?= html::anchor('home', html::image('images/buttons/back.gif')) ?></p> 
	</center>

</div>

<?php 
echo form::close();
?>
