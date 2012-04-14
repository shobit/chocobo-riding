<?php defined('SYSPATH') OR die('No direct access allowed.');

class Circuit_Controller extends Template_Controller {

	// FUNC: liste de toutes les courses de classe du chocobo utilisé
	public function index() 
	{
		$this->authorize('logged_in');
		
		$chocobo = $this->session->get('chocobo');
		
		if ($chocobo->classe <= 6)
		{
			for ($i=0; $i<=2; $i++) 
			{
				$list = Circuit_Model::get_list($i, $chocobo->classe);
				foreach ($list as $circuit) $circuit->revise(false);
			}
			
			Circuit_Model::create($chocobo->classe);
		}
		
        $index = new View('circuits/index');
		$index->chocobo = $chocobo;
		for ($i=0; $i<=2; $i++) 
		{
			$index->{'circuits_'.$i} = ORM::factory('circuit')
				->where('race', $i)
				->where('classe', $chocobo->classe)
				->where('status <=', 1)
				->where('finished', 0)
				->find_all();
		}
		
		$index->last_circuits = ORM::factory('circuit')
			->where('status', 2)
			->where('finished', 0)
			->find_all();
		
		$index->last_results = ORM::factory('result')
			->where('chocobo_id', $chocobo->id)
			->orderby('id', 'desc')
			->find_all();
		
		$this->template->content = $index;
	}
	
	// FUNC: Vue d'un circuit
	// var $id INT
	public function view($id) 
	{
		$this->authorize('logged_in');
		$circuit = ORM::factory('circuit', $id);
		$chocobo = $this->session->get('chocobo');
		if ($circuit->id > 0) $circuit->revise();
		if ($circuit->id >0) {
			switch($circuit->status) {
				case 0:
					$view = new View('circuits/view_start');
					$view->register   = $this->_can_register($chocobo, $circuit);
					$view->unregister = $this->_can_unregister($chocobo, $circuit);
					break;
				case 1:
					$view = new View('circuits/view_bets');
					break;
				case 2:
					$results = ORM::factory('result')
						->where('circuit_id', $circuit->id)
						->orderby('position', 'asc')
						->find_all();
					$query =  ORM::factory('fact');
					foreach($results as $result) $query->orwhere("result_id", $result->id);
					$facts = $query->orderby('action', 'asc')->find_all();
					$view 			= new View('circuits/view_results');
					$view->results 	= $results;
					$view->facts 	= $facts;
					break;
			}
			$view->wave					= new View('elements/wave', array('id' => $id));
			$view->circuit 				= ORM::factory('circuit', $id);
			$view->chocobo	 			= $chocobo;
			$this->template->content 	= $view;
		} else {
			url::redirect('circuit');
		}
	}
	
	// FUNC:
	// var $id INT
	public function register($id) 
	{
		$this->authorize('logged_in');
		$circuit = ORM::factory('circuit', $id);
		$chocobo = $this->session->get('chocobo');
		if ($this->_can_register($chocobo, $circuit)) {
			$chocobo->circuit_id = $circuit->id;
			$chocobo->status 	 = 3;
			$chocobo->save();
		}	
		url::redirect('circuit/view/'.$id);
	}
	
	// FUNC:
	// var $id INT
	public function unregister($id) 
	{
		$this->authorize('logged_in');
		$circuit = ORM::factory('circuit', $id);
		$chocobo = $this->session->get('chocobo');
		if ($this->_can_unregister($chocobo, $circuit)) {
			$chocobo->circuit_id = null;
			$chocobo->status 	 = 0;
			$chocobo->save();
		}
		url::redirect('circuit/view/'.$id);
	}
	
	// FUNC:
	// var $chocobo OBJECT
	// var $circuit OBJECT
	public function _can_register($chocobo, $circuit) 
	{
		return (
			($chocobo->status == 0) and 
			($chocobo->classe == $circuit->classe) and 
			($chocobo->breath >= $circuit->length) and 
			($circuit->status == 0) and 
			($circuit->no_same_user($chocobo->user->id) or $circuit->race == 2) and 
			(count($circuit->chocobos) < 6) and 
			(time() < $circuit->start)
		);
	}
	
	// FUNC:
	// var $chocobo OBJECT
	// var $circuit OBJECT
	public function _can_unregister($chocobo, $circuit) 
	{
		return (
			($circuit->status == 0) and 
			($chocobo->circuit_id == $circuit->id) 
			//($circuit->start-time() > 15*60)
		);
	}

}