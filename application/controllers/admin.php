<?php
class Admin_Controller extends Template_Controller {

	/**
	 * Admin index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index() 
	{
		// access
		$this->authorize('admin');
		$user_session = $this->session->get('chocobo');
		
		// view
		$this->template->content = new View('admin/index');

	}
	
	public function api()
	{
		$this->authorize('admin');
		$post = new Validation($_POST);
		$post->add_rules('username', 'required');
		
		if ($post->validate())
		{
			$user = ORM::factory('user')
				->where('username', $post->username)
				->find();
			
			if ($user->loaded)
			{
				if (empty($user->api))
				{
					$user->api = uniqid();
					$user->save();
					gen::add_jgrowl("Clé API atribuée!");
				}
				else
				{
					$user->api = "";
					$user->save();
					gen::add_jgrowl("Clé API supprimée!");
				}
			}
			else
			{
				gen::add_jgrowl("User non trouvé");
			}
			
		}
		else
		{
			gen::add_jgrowl("Données POST manquantes");
		}
		
		url::redirect('admin/index');
	}

}