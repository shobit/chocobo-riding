<?php 

echo '<h1>Gestion des joueurs</h1>';

foreach ($users as $user)
{	
	echo $user->id .'. '. $user->username;

	if ($user->id != $admin->id) {
		echo ' (' . html::anchor(
			'admin/user/delete/'.$user->id,
			Kohana::lang('administration.delete'),
			array(
				'onclick' => 'return confirm('+Kohana::lang('administration.confirm')+')'
			)
		) . ')';
	}

	echo '<br />';
}