<?php

class User_Controller extends Admin_Controller 
{

	// liste tous les joueurs
	public function index ()
	{
		$this->template->content = View::factory('admin/users/index')
			->bind('users', $users)
			->bind('admin', $admin);
		
		$users = ORM::factory('user')->find_all();
		
		$admin = $this->session->get('user');
	}
	
    // supprime un joueur
    public function delete ( $id )
    {
		$user = ORM::factory('user', $id);
		$user->delete();
		
		url::redirect('admin/users');
	}

}