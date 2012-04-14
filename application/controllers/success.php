<?php
class Success_Controller extends Template_Controller {

	public function view($name="")
	{
		$this->authorize('logged_in');
		$view = new View('successes/view');
		$user_session = $this->session->get('user');
		$view->user_session = $user_session;
		$view->user = (empty($name)) ? $user_session 
			: $user = ORM::factory('user')
				->where('username', $name)
				->find();
		$view->chocobo = $this->session->get('chocobo');
		$this->template->content = $view;
	}
	
}