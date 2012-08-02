<?php
class Chocobo_Controller extends Template_Controller {

	/**
	 * Vue de tous les chocobos
	 */
	public function index()
	{
		$this->template->content = View::factory('chocobos/index')
			->bind('chocobos', $chocobos)
			->bind('c', $c);

		$c = $this->session->get('chocobo');

		$chocobos = ORM::factory('chocobo')
			->where('name !=', '')
			->find_all();
	}

	// FUNC: vue de la fiche d'un chocobo
	// var $name STRING
	public function view($id, $section='')
	{
		$this->authorize('logged_in');
		
		$this->template->content = View::factory('chocobos/view')
			->bind('user', $u)
			->bind('chocobo_session', $c)
			->bind('chocobo', $chocobo);
		
		$u = $this->session->get('user');
		$c = $this->session->get('chocobo');
		
		$chocobo = ORM::factory('chocobo', $id);
	}

	// FUNC: change de chocobo principal
	// var $name STRING
	public function change($name)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$chocobos = ORM::factory('chocobo')->where('user_id', $user->id)->select_list('id', 'name');
		if (in_array($name, $chocobos)) {
			$chocobo = ORM::factory('chocobo')->where('name', $name)->find();
			$last_chocobo = $this->session->get('chocobo');
			$this->session->set('chocobo', $chocobo);
			$link = 'chocobo/view/'.$name;
			$url = explode('/', request::referrer());
			if ($chocobo->id != $last_chocobo->id and
				request::referrer() != 'chocobo/view/'.$last_chocobo->name)
				$link = request::referrer();
			//if (request::referrer() == 'circuit/view/'.$last_chocobo->name)
			// url::redirect('circuit');
			url::redirect($link);
		} else {
			url::redirect(request::referrer());
		}
	}

	// FUNC: éditer la fiche du chocobo
	public function edit()
	{
		$this->authorize('logged_in');
		$chocobo = $this->session->get('chocobo');
		$form = array(
			'name' => $chocobo->name
		);
		$errors = $form;

		if ($_POST) {
			$post = new Validation($_POST);
			$post->add_rules('name', 'required', 'length[4,12]', 'alpha_dash');
			$post->add_callbacks('name', array($this, '_unique_name'));
			if ($post->validate()) {
				$chocobo->name = $post->name;
				$chocobo->save();
				url::redirect('chocobo/view/'.$chocobo->name);
			} else {
				$form = arr::overwrite($form, $post->as_array());
				$errors = arr::overwrite($errors, $post->errors('form_error_messages'));
			}
		}

		$edit = new View('chocobos/edit');
		$edit->chocobo = $chocobo;
		$edit->errors = $errors;
		$edit->form = $form;
		$this->template->content = $edit;
	}
	
	public function _unique_name(Validation $array, $field) {
		$name_exists = (bool) ORM::factory('chocobo')->where('name', $array[$field])->count_all();

		if ($name_exists) {
			$array->add_error($field, 'name_exists');
		}
	}

	public function aptitude_up($apt)
	{
		$chocobo = $this->session->get('chocobo');
		$apts = array("speed", "endur", "intel");
		$res = FALSE;
		if (in_array($apt, $apts) and $chocobo->points >0) {
			$apts = array_flip($apts);
			$chocobo->$apt ++;
			$chocobo->points --;
			$chocobo->save();
			$res = TRUE;
		}
		// Traitement Ajax ou pas
		if (!$res or !request::is_ajax()) {
			url::redirect("chocobo/view/".$chocobo->name);
		}
		else {
			echo $chocobo->points;

			$this->profiler->disable();
			$this->auto_render = false;
			header('content-type: application/json');
		}
	}

	/**
	 * Achète un chocobo pour le joueur en session
	 *
	 * @param int $id ID du chocobo à acheter
	 */
	public function buy($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');

		$chocobo = ORM::factory('chocobo', $id);
		$cost = $chocobo->get_price();

		if ( ! $chocobo->loaded)
		{
			$msg = 'chocobo_not_found';
		}

		if ($chocobo->user_id != 0)
		{
			$msg = 'chocobo_not_purchasable';
		}

		if ($user->gils < $cost)
		{
			$msg = 'not_enough_gils';
		}

		if (count($user->chocobos) >= $user->get_boxes())
		{
			$msg = 'no_more_boxes';
		}

		if ( ! isset($msg))
		{
			$chocobo->user_id = $user->id;
			$chocobo->save();
			$user->set_gils(-$cost);
			$user->save();
			$msg = 'Chocobo acheté!';
		}
		
		gen::add_jgrowl($msg);
		url::redirect('shop');
	}

	/**
	 * Vend un chocobo qui appartient au joueur en session
	 * 
	 * @param int $id ID du chocobo à vendre
	 */
	public function sale($id) 
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');
		
		$chocobo = ORM::factory('chocobo', $id);
		
		if ( ! $chocobo->loaded) 
		{
			$msg = 'chocobo_not_found';
		}
		
		if ($chocobo->user_id != $user->id)
		{
			$msg = 'chocobo_not_owned';
		}
		
		if (count($user->chocobos) == 1)
		{
			$msg = 'chocobo_alone';
		}
		
		if ( ! isset($msg)) 
		{
			$price = $chocobo->get_price();
			$user->set_gils($price);
			$user->nbr_chocobos_saled++;
			$user->save();
			
			$chocobo->delete();
			$msg = 'Chocobo vendu!';
			
			$user->reload();
			$chocobo = $user->chocobos[0];
			if ($chocobo->id == $id) $chocobo = $user->chocobos[1];
			$this->session->set('chocobo', $chocobo);
		}
		
		gen::add_jgrowl($msg);
		url::redirect('chocobo/view/' . $chocobo->name);
	}

}