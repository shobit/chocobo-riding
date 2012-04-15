<?php defined('SYSPATH') OR die('No direct access allowed.');

class Circuit_Controller extends Template_Controller {

	/*
	 * (void) liste toutes les courses de la classe du chocobo en session
	 *
	 */
	public function index ( ) 
	{
		$this->authorize('logged_in');
		
		$this->template->content = View::factory("circuits/index")
			->bind('classe', $classe)
			->bind('circuits', $circuits)
			->bind('results', $results);
		
		// Détection de le classe du chocobo en session
		$chocobo = $this->session->get('chocobo');
		$classe = $chocobo->classe;
		
		// Heure courante
		$date = time();
		
		// Recherche des courses officielles
		$official_circuits = ORM::factory('circuit')
			->where('classe', $classe)
			->where('owner', 0)
			->find_all();
			
		// Suppression des courses officielles "vides"
		$nb = 0;
		foreach ($official_circuits as $circuit)
		{
			// Si le départ est passé et qu'il n'y a aucun chocobos sur le départ
			if ($circuit->start <= $date) 
			{
				if (empty($circuit->script) and count($circuit->chocobos) == 0)
				{
					$circuit->delete();
				}
			}
			else 
			{
				$nb++;
			}
		}
		
		// On complète le nbre de couses officielles à 4
		for ($i = $nb; $i < 4; $i++)
		{
			ORM::factory('circuit')->add($i, $classe);
		}
		
		// On liste toutes les courses à venir		
		$circuits = ORM::factory('circuit')
			->where('classe', $classe)
			->where('start >', $date)
			->find_all();
			
		$results = ORM::factory('result')
			->where('chocobo_id', $chocobo->id)
			->where('deleted', FALSE)
			->orderby('id', 'desc')
			->find_all();
	}
	
	/*
	 * (void) affiche le profil d'un circuit
	 * 
	 * (int) $id
	 */
	public function view($id) 
	{
		$this->authorize('logged_in');
		
		$chocobo = $this->session->get('chocobo');
		
		$circuit = ORM::factory('circuit', $id);
		
		if ( ! $circuit->loaded)
		{
			url::redirect('circuit/index');
		}
		
		// Heure courante
		$date = time();
		
		// Mettre à jour la course
		if ($circuit->start <= $date) 
		{
			if (empty($circuit->script) and count($circuit->chocobos) == 0)
			{
				$circuit->delete();
				url::redirect('circuit/index');
			}
			
			foreach ($circuit->results as $result)
			{
				$result = $result->as_array();
				$results[] = $result;
			}
			
			$result = ORM::factory('result')
				->where('chocobo_id', $chocobo->id)
				->where('circuit_id', $id)
				->find();
			if ( ! $result->seen)
			{
				$result->seen = TRUE;
				$result->save();
			}
			
			#TODO facts
			
			$wave = new View('elements/wave', array('id' => $id));
			
			$this->template->content = View::factory("circuits/view_results")
				->bind('circuit', $circuit)
				->bind('chocobo', $chocobo)
				->bind('wave', $wave)
				->bind('results', $results);
		} 
		else 
		{
			$can_register = $this->_can_register($chocobo, $circuit);
			
			$can_unregister = $this->_can_unregister($chocobo, $circuit);
			
			$wave = new View('elements/wave', array('id' => $id));
			
			$this->template->content = View::factory("circuits/view_start")
				->bind('circuit', $circuit)
				->bind('chocobo', $chocobo)
				->bind('wave', $wave)
				->bind('can_register', $can_register)
				->bind('can_unregister', $can_unregister);				
		}
		
		/*
		$results = ORM::factory('result')
			->where('circuit_id', $circuit->id)
			->orderby('position', 'asc')
			->find_all();
		$query =  ORM::factory('fact');
		foreach($results as $result) $query->orwhere("result_id", $result->id);
		$facts = $query->orderby('action', 'asc')->find_all();
		*/					
	}
	
	/*
	 * (Array) inscrit le chocobo en session au circuit
	 *
	 * (Int) 	$id			ID du circuit
	 */
	public function register ( $id = 0 ) 
	{
		$this->authorize('logged_in');
		
		$circuit = ORM::factory('circuit', $id);
		
		$chocobo = $this->session->get('chocobo');
		
		$r = $this->_can_register($chocobo, $circuit);
		
		if ($r['success'])
		{
			$chocobo->circuit_id = $id;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('circuits/' . $id);
		}
		else 
		{
			echo json_encode($r);
			
			$this->profiler->disable();
			$this->auto_render = false;
			header('content-type: application/json');
		}
	}
	
	/*
	 * (Array) désinscrit le chocobo en session du circuit
	 *
	 * (Int) 	$id			ID du circuit
	 */
	public function unregister ( $id = 0 ) 
	{
		$this->authorize('logged_in');
		
		$circuit = ORM::factory('circuit', $id);
		
		$chocobo = $this->session->get('chocobo');
		
		$r = $this->_can_unregister($chocobo, $circuit);
		
		if ($r['success'])
		{
			$chocobo->circuit_id = 0;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('circuits/' . $id);
		}
		else 
		{
			echo json_encode($r);
			
			$this->profiler->disable();
			$this->auto_render = false;
			header('content-type: application/json');
		}
	}
	
	/*
	 * (Array) indique si le chocobo peut s'inscrire au circuit
	 *
	 * (Object) $chocobo	Objet chocobo
	 * (Int) 	$id			ID du circuit
	 */
	public function _can_register( $chocobo, $circuit ) 
	{
		$msg = '';
		
		if ( ! $circuit->loaded)
		{
			$msg = 'circuit_not_found';
		}
		
		$date = time();
		
		if ($circuit->start <= $date)
		{
			$msg = 'circuit_started';
		}
		
		if ($chocobo->classe !== $circuit->classe)
		{
			$msg = 'classe_not_matching';
		}
		
		if (count($circuit->chocobos) >= 6)
		{
			$msg = 'full_circuit';
		}
		
		if ( ! empty($chocobo->circuit_id))
		{
			$msg = 'chocobo_not_free';
		}
		
		#TODO breath > length
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}
	
	/*
	 * (Array) indique si le chocobo peut se désinscrire du circuit
	 *
	 * (Object) $chocobo	Objet chocobo
	 * (Int) 	$id			ID du circuit
	 */
	public function _can_unregister( $chocobo, $circuit ) 
	{
		$msg = '';
		
		if ( ! $circuit->loaded)
		{
			$msg = 'circuit_not_found';
		}
		
		$date = time();
		
		if ($circuit->start <= $date)
		{
			$msg = 'circuit_started';
		}
		
		if ($chocobo->circuit_id !== $circuit->id)
		{
			$msg = 'not_registered';
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}

}