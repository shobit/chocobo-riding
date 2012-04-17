<?php defined('SYSPATH') or die('No direct script access.');
 
class Race_Model extends ORM {
    
    protected $has_many = array('chocobos', 'results', 'waves');
    protected $has_one = array('circuit');
	protected $load_with = array('circuit');
    
    public function no_same_user($user_id) 
	{
		$nbr_same_chocobos = 
			ORM::factory('chocobo')
			   ->where('user_id', $user_id)
			   ->where('race_id', $this->id)
			   ->count_all();
		return ($nbr_same_chocobos == 0);
	}
    
    /*
     * (void) ajoute une nouvelle course
	 *
	 * (int) $classe
	 */
	public function add ( $classe )
	{
		// TODO Choix d'un lieu au hasard
		$circuit = ORM::factory('circuit')
			->where('classe', $classe)
			->orderby(NULL, 'RAND()')
			->limit(1)
			->find();
		
		// Détermination de l'heure de départ (chaque quart d'heure)
		$tps = 15*60;
        $base = floor( time() / $tps );
		$last = $base * $tps;
		$start = $last + $tps;
		
		$this->circuit_id 	= $circuit->id;
		$this->start 		= $start;
		
		$this->save();
	}
	
	public function delete()
	{
		foreach ($this->results as $result) $result->delete();
		foreach ($this->waves as $wave) $wave->delete();
		parent::delete();
	}

}
