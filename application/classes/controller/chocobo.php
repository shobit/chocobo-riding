<?php
class Controller_Chocobo extends Controller_Template {

	/**
	 * Vue de tous les chocobos
	 */
	public function action_index()
    {
		$this->authorize('logged_in');

		$c = Session::instance()->get('chocobo');

		$chocobos = Model_Chocobo::get_chocobos();

		$this->template->content = View::factory('chocobos/index')
			->set('chocobos', $chocobos)
			->set('c', $c);
	}

	/**
	 * Vue profil chocobo
	 * 
	 * @param $id int ID du chocobo
	 * @param $section string Section à afficher
	 */
	public function action_view()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		$section = $this->request->param('section');

		$u = Auth::instance()->get_user();
		$c = Session::instance()->get('chocobo');

		$chocobo = ORM::factory('chocobo', $id);

		$this->template->content = View::factory('chocobos/view')
			->set('user', $u)
			->set('chocobo_session', $c)
			->set('chocobo', $chocobo);
	}

	/**
	 * ACTION: changer de chocobo principal (privé)
	 * 
	 * @param $id int ID du chocobo
	 */
	public function action_change()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$user = Auth::instance()->get_user();

		$chocobo = ORM::factory('chocobo', $id);

		// Redirection si le chocobo n'appartient pas au joueur en session
		if ($chocobo->user_id !== $user->id)
		{
			$this->request->redirect('chocobos/'.$id);
		}

		$last_chocobo = Session::instance()->get('chocobo');
		
		Session::instance()->set('chocobo', $chocobo);
		
		$link = 'chocobos/'.$id;
		$referrer = $this->request->referrer();

		if ($chocobo->id !== $last_chocobo->id and $referrer !== $link)
		{
			$link = $referrer;
		}

		$this->request->redirect($link);
	}

	/**
	 * Vue éditer un chocobo (privé)
	 * 
	 * @param $id int ID du chocobo
	 */
	public function action_edit()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();

		$chocobo = ORM::factory('chocobo', $id);

		// Redirection si le chocobo n'appartient pas au joueur en session
		if ($chocobo->user_id !== $user->id)
		{
			$this->request->redirect('chocobos/'.$id);
		}

		// Redirection si le nom du chocobo a déjà été choisi
		if ($chocobo->name != '')
		{
			$this->request->redirect('chocobos/'.$id);
		}
		
		$post = Validation::factory($_POST)
			->rule('name', 'not_empty')
			->rule('name', 'Valid::alpha_dash')
			->rule('name', array($chocobo, 'unique'), array('name', ':value'));

		if ($_POST)
		{
			$values = $_POST;
		}

		if ($_POST AND $post->check()) 
		{
			$chocobo->update_name($post);
		
			$this->request->redirect('chocobos/'.$chocobo->id);
		}

		$this->template->content = View::factory('chocobos/edit')
			->bind('values', $values)
			->set('chocobo', $chocobo);
	}

	/**
	 * ACTION: Augmente une des aptitudes du chocobo
	 * URL: chocobos/<id>/up/<apt>
	 *
	 * @param $id int ID du chocobo
	 * @param $apt string (speed|endur|intel)
	 */
	public function action_boost()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		$apt = $this->request->param('apt');

		$user = Auth::instance()->get_user();

		$chocobo = ORM::factory('chocobo', $id);
		
		// Si le chocobo appartient bien au joueur en session
		if ($chocobo->user_id === $user->id)
		{
			$chocobo->boost($apt);
		}

		$this->request->redirect('chocobos/'.$chocobo->id.'#/details');
	}

	/**
	 * ACTION: achète un chocobo pour le joueur en session
	 *
	 * @param $id int ID du chocobo
	 */
	public function action_buy()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$user = Auth::instance()->get_user();

		$chocobo = ORM::factory('chocobo', $id);
		
		$msg = $chocobo->buy($user);

		Jgrowl::add($msg);
		
		$this->request->redirect('shop');
	}

	/**
	 * ACTION: vend un chocobo qui appartient au joueur en session
	 * 
	 * @param int $id ID du chocobo
	 */
	public function action_sale() 
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');
		
		$user = Auth::instance()->get_user();

		$chocobo = ORM::factory('chocobo', $id);

		$msg = $chocobo->sale($user);

		Jgrowl::add($msg);

		$this->request->redirect('users/'.$user->id.'#/chocobos');
	}

}