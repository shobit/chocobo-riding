<?php defined('SYSPATH') or die('No direct script access.');
 
class Circuit_Model extends ORM {
    
    protected $has_many = array('chocobos', 'results', 'waves');
    protected $has_one = array('location');
    
    public function no_same_user($user_id) 
	{
		$nbr_same_chocobos = 
			ORM::factory('chocobo')
			   ->where('user_id', $user_id)
			   ->where('circuit_id', $this->id)
			   ->count_all();
		return ($nbr_same_chocobos == 0);
	}
    
    /*
     * (void) ajoute une nouvelle course
	 *
	 * (int) $nieme 	
	 * (int) $classe
	 * (int) $user_id
	 */
	public function add ( $nieme, $classe, $user_id = 0 )
	{
		// TODO Choix d'un lieu au hasard
		$location = ORM::factory('location')
			->where('classe <=', $classe)
			->orderby(NULL, 'RAND()')
			->find(1);
		
		// Détermination de l'heure de départ
		$tps = 15*60;
        $base = floor( time() / $tps );
		$last = $base * $tps;
		$start = $last + $tps * ($nieme + 1);
		
		$this->location_id 	= $location->id;
		$this->classe		= $classe;
		$this->pl			= 10;
		$this->length		= 600;
		$this->start 		= $start;
		$this->owner 		= $user_id;
		
		$this->save();
	}
	
	public function delete()
	{
		foreach ($this->chocobos as $chocobo) $chocobo->delete();
		foreach ($this->results as $result) $result->delete();
		foreach ($this->waves as $wave) $wave->delete();
		parent::delete();
	}

}
