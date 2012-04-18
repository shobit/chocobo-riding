<?php defined('SYSPATH') OR die('No direct access allowed.');

class Race_Controller extends Template_Controller {

	/*
	 * (void) liste toutes les courses de la classe du chocobo en session
	 *
	 */
	public function index ( ) 
	{
		$this->authorize('logged_in');
		
		$this->template->content = View::factory('races/index')
			->bind('classe', $classe)
			->bind('races', $races)
			->bind('results', $results);
		
		// Détection de le classe du chocobo en session
		$chocobo = $this->session->get('chocobo');
		$classe = $chocobo->classe;
		
		// Heure courante
		$date = time();
		
		// Suppression des courses passées "vides" (s'il n'y a aucun chocobos sur le départ)
		$last_races = ORM::factory('race')
			->where('classe', $classe)
			->where('start <=', $date)
			->find_all();
			
		$nb = 0;
		foreach ($last_races as $race)
		{
			if (empty($race->script) and count($race->chocobos) == 0)
			{
				$race->delete();
			}
		}
		
		// On liste toutes les courses à venir		
		$races = ORM::factory('race')
			->where('classe', $classe)
			->where('start >', $date)
			->find_all();
		
		if (count($races) == 0)
		{
			// On génère de nouvelles courses
			for ($i = 0; $i < 6; $i++)
			{
				ORM::factory('race')->add($classe);				
			}
			
			$races = ORM::factory('race')
				->where('classe', $classe)
				->where('start >', $date)
				->find_all();
		}
		
		$results = ORM::factory('result')
			->where('chocobo_id', $chocobo->id)
			->where('deleted', FALSE)
			->orderby('id', 'desc')
			->find_all();
	}
	
	/*
	 * (void) affiche le profil d'un course
	 * 
	 * (int) $id
	 */
	public function view ( $id ) 
	{
		$this->authorize('logged_in');
		
		$chocobo = $this->session->get('chocobo');
		
		$race = ORM::factory('race', $id);
		
		if ( ! $race->loaded)
		{
			url::redirect('races');
		}
		
		// Heure courante
		$date = time();
		
		// Mettre à jour la course
		if ($race->start <= $date) 
		{
			if (empty($race->script) and count($race->chocobos) == 0)
			{
				$race->delete();
				url::redirect('races');
			}
			
			foreach ($race->results as $result)
			{
				$result = $result->as_array();
				$results[] = $result;
			}
			
			$result = ORM::factory('result')
				->where('chocobo_id', $chocobo->id)
				->where('race_id', $id)
				->find();
			if ( ! $result->seen)
			{
				$result->seen = TRUE;
				$result->save();
			}
			
			#TODO facts
			
			$wave = new View('elements/wave', array('id' => $id));
			
			$this->template->content = View::factory("races/view_results")
				->bind('race', $race)
				->bind('chocobo', $chocobo)
				->bind('wave', $wave)
				->bind('results', $results);
		} 
		else 
		{
			$can_register = $this->_can_register($chocobo, $race);
			
			$can_unregister = $this->_can_unregister($chocobo, $race);
			
			$wave = new View('elements/wave', array('id' => $id));
			
			$this->template->content = View::factory("races/view_start")
				->bind('race', $race)
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
	 * (Array) inscrit le chocobo en session au course
	 *
	 * (Int) 	$id			ID du course
	 */
	public function register ( $id = 0 ) 
	{
		$this->authorize('logged_in');
		
		$race = ORM::factory('race', $id);
		
		$chocobo = $this->session->get('chocobo');
		
		$r = $this->_can_register($chocobo, $race);
		
		if ($r['success'])
		{
			$chocobo->race_id = $id;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('races/' . $id);
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
	 * (Array) désinscrit le chocobo en session du course
	 *
	 * (Int) 	$id			ID du course
	 */
	public function unregister ( $id = 0 ) 
	{
		$this->authorize('logged_in');
		
		$race = ORM::factory('race', $id);
		
		$chocobo = $this->session->get('chocobo');
		
		$r = $this->_can_unregister($chocobo, $race);
		
		if ($r['success'])
		{
			$chocobo->race_id = 0;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('races/' . $id);
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
	 * (Array) indique si le chocobo peut s'inscrire au course
	 *
	 * (Object) $chocobo	Objet chocobo
	 * (Int) 	$id			ID du course
	 */
	public function _can_register( $chocobo, $race ) 
	{
		$msg = '';
		
		if ( ! $race->loaded)
		{
			$msg = 'race_not_found';
		}
		
		$date = time();
		
		if ($race->start <= $date)
		{
			$msg = 'race_started';
		}
		
		if ($chocobo->classe !== $race->circuit->classe)
		{
			$msg = 'classe_not_matching';
		}
		
		if (count($race->chocobos) >= 6)
		{
			$msg = 'full_race';
		}
		
		if ( ! empty($chocobo->race_id))
		{
			$msg = 'chocobo_not_free';
		}
		
		if ($chocobo->pl < $race->circuit->pl)
		{
			$msg = 'chocobo_tired';
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}
	
	/*
	 * (Array) indique si le chocobo peut se désinscrire du course
	 *
	 * (Object) $chocobo	Objet chocobo
	 * (Int) 	$id			ID du course
	 */
	public function _can_unregister( $chocobo, $race ) 
	{
		$msg = '';
		
		if ( ! $race->loaded)
		{
			$msg = 'race_not_found';
		}
		
		$date = time();
		
		if ($race->start <= $date)
		{
			$msg = 'race_started';
		}
		
		if ($chocobo->race_id !== $race->id)
		{
			$msg = 'not_registered';
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}
	
	/**
	 * Supprime la course de l'historique du chocobo en session
	 * et supprime la course si tous les autres participants
	 * l'ont fait.
	 *
	 * (void) Redirige ou retourne du texte si c'est en ajax
	 */
	public function delete ()
	{
		$chocobo = $this->session->get('chocobo');
		
		$errors = array();
		
		$id = $this->input->post('id', 0);
		
		$result = ORM::factory('result')
			->where('chocobo_id', $chocobo->id)
			->where('race_id', $id)
			->find();
			
		$msg = '';
		
		if ( ! $result->loaded)
		{
			$msg = 'result_not_found';
		}
		
		if ( ! $result->seen)
		{
			$msg = 'result_not_seen';
		}
		
		if (empty($msg))
		{
			$nbr_results = ORM::factory('result')
				->where('race_id', $id)
				->count_all();
			
			$result->deleted = TRUE;
			$result->save();
			
			$nbr_results --;
			
			if ($nbr_results == 0)
			{
				ORM::factory('race', $id)->delete();
			}
		}
		
		if ( ! request::is_ajax()) 
		{
			url::redirect('races');
		}
		else
		{
			$res['success'] = empty($msg);
			$res['msg'] = $msg;
			echo json_encode($res);
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}

}