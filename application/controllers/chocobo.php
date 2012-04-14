<?php
class Chocobo_Controller extends Template_Controller {

	// FUNC: vue de la fiche d'un chocobo
	// var $name STRING
	public function view($name="")
	{
		$this->authorize('logged_in');
		if ($name != null) {
			$view = new View('chocobos/view');
			$user = $this->session->get('user');
			$chocobo = $this->session->get('chocobo');
			$view->user = $user;
			$view->chocobo_session = $chocobo;
			$view->chocobo = ($name == $chocobo->name)
				? $chocobo
				: ORM::factory('chocobo')->where('name', $name)->find();
			$this->template->content = $view;
		} else {
			url::redirect('chocobo/index');
		}
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
	 * sale function.
	 * 
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	public function sale($name) {
		// access
		$this->authorize('logged_in');
		$chocobo_session = $this->session->get('chocobo');
		
		// var
		$user = $this->session->get('user');
		$chocobo = ORM::factory('chocobo')->where('name', $name)->find();
		
		// verifying
		if ($chocobo->id >0 and $chocobo->user->id == $user->id and count($user->chocobos) > 1) {
			$price = $chocobo->get_price();
			$user->gils += $price;
			
			// jgrowl			
			gen::add_jgrowl('Vente - Chocobo '.$chocobo->name.' vendu ! ('.$price.' Gils)');
			
			// updating user 
			$user->listen_success(array( # SUCCES
				"gils_500",
				"gils_1000",
				"gils_5000",
				"gils_10000"
			));
			$user->nbr_chocobos_saled += 1;
			$user->save();
			
			// deleting chocobo
			$chocobo->delete();
			
			// selecting first chocobo
			if ($chocobo_session->id == $chocobo->id) {
				$this->session->set('chocobo', $user->chocobos[0]);
			}
		}
		
		// redirecting
		url::redirect('chocobo/view/'.$chocobo_session->name);

	}

}