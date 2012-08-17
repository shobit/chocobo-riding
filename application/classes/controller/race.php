<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Race extends Controller_Template {

	/**
	 * Vue des courses
	 */
	public function action_index() 
	{
		$this->authorize('logged_in');
		
		// Détection de le classe du chocobo en session
		$chocobo = Session::instance()->get('chocobo');
		$classe = $chocobo->classe;
		
		// Maintenance des courses "vides"
		Model_Race::clean_races($classe);
		
		// Liste des courses à venir
		$races = Model_Race::get_races($classe);
		
		$results = $chocobo->get_results();

		$this->template->content = View::factory('races/index')
			->set('classe', $classe)
			->set('races', $races)
			->set('results', $results);
	}
	
	/**
	 * Vue de la course
	 * 
	 * @param $id int ID de la course
	 */
	public function action_view()  
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$chocobo = Session::instance()->get('chocobo');
		$classe = $chocobo->classe;
		
		// Maintenance des courses "vides"
		Model_Race::clean_races($classe);

		$race = ORM::factory('race', $id);
		$race->simulate();

		if ( ! $race->loaded())
		{
			$this->request->redirect('races');
		}

		// Mettre à jour la course
		if ($race->start <= time()) 
		{
			Model_Result::mark_as_seen($race->id, $chocobo->id);
			
			#TODO facts
			
			$this->template->content = View::factory("races/view_results")
				->set('race', $race)
				->set('chocobo', $chocobo);
		} 
		else 
		{
			$_register = $chocobo->_register($race);
			
			$_unregister = $chocobo->_unregister($race);

			$this->template->content = View::factory("races/view_start")
				->bind('race', $race)
				->bind('chocobo', $chocobo)
				->bind('_register', $_register)
				->bind('_unregister', $_unregister);				
		}
	}
	
	/**
	 * ACTION: inscrit le chocobo en session à la course
	 *
	 * @param $id int ID de la course
	 */
	public function action_register() 
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$race = ORM::factory('race', $id);
		
		$chocobo = Session::instance()->get('chocobo');
		
		$r = $chocobo->register($race);
		
		if ($r['success'] === FALSE)
		{
			Jgrowl::add($r['msg']);
		}
		
		$this->request->redirect('races/'.$id);
	}
	
	/**
	 * ACTION: désinscrit le chocobo en session de la course
	 *
	 * @param $id int ID de la course
	 */
	public function action_unregister() 
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$race = ORM::factory('race', $id);
		
		$chocobo = Session::instance()->get('chocobo');
		
		$r = $chocobo->unregister($race);
		
		if ($r['success'] === FALSE)
		{
			Jgrowl::add($r['msg']);
		}
		
		$this->request->redirect('races/'.$id);
	}

}