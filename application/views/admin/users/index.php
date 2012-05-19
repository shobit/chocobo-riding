<?php 

echo '<h1>Gestion des joueurs</h1>';

echo '<table>';
foreach ($users as $user)
{	
	echo '<tr>';
	
	echo '<td>' . $user->id . '</td>';
	echo '<td>' . $user->username . '</td>';
	
	echo '<td>'. date::display($user->created) . '</td>';
	echo '<td>';
	if ($user->connected > 0)
	{
		echo date::display($user->connected);
	}
	else
	{
		echo '-';
	}
	echo '</td>';

	echo '<td>';
	if ($user->id != $admin->id) 
	{
		echo '<td>' . html::anchor(
			'admin/user/delete/'.$user->id,
			html::image('images/icons/delete.png'),
			array(
				'onclick' => "return confirm('" . Kohana::lang('administration.confirm') . "')"
			)
		);
	}
	echo '</td>';
	
	echo '<td>';
	if ($user->deleted > 0)
	{
		echo '<small>à supprimer</small>';
	}
	echo '</td>';

	echo '</tr>';
}
echo '</table>';