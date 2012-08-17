<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Race extends ORM {
    
    protected $_has_many = array('chocobos' => array(), 'results' => array(), 'waves' => array());
    protected $_belongs_to = array('circuit' => array());

	/**
	 * Supprime les courses passées d'une $classe
	 * qui sont vides (s'il n'y a aucun chocobos sur le départ)
	 * 
	 * @param $classe int ID de la classe
	 * @return void
	 */
	public static function clean_races($classe)
	{
		$races = ORM::factory('race')
			->join('circuits')
			->on('circuits.id', '=', 'race.circuit_id')
			->where('circuits.classe', '=', $classe)
			->where('start', '<=', time())
			->find_all();
			
		foreach ($races as $race)
		{
			if (empty($race->script) AND $race->chocobos->count_all() == 0)
			{
				$race->delete();
			}
		}
	}

	/**
	 * Récupère la liste des courses à venir d'une $classe
	 * 
	 * @param $classe int ID de la classe
	 * @return Model_Race object
	 */
	public static function get_races($classe)
	{
		$v = ORM::factory('race')
			->join('circuits')
			->on('circuits.id', '=', 'race.circuit_id')
			->where('circuits.classe', '=', $classe)
			->where('start', '>', time());

		$x = clone $v;
		$count = $x->count_all();
		
		if ($count == 0)
		{
			for ($i = 0; $i < 6; $i++)
			{
				Model_Race::generate($classe);
			}
		}

		return $v->find_all();
	}
    
    /**
     * Génère une course de $classe
	 *
	 * @param $classe int ID de la classe
	 * @return void 
	 */
	public static function generate($classe)
	{
		// TODO choix d'un lieu au hasard
		$circuit = ORM::factory('circuit')
			->where('classe', '=', $classe)
			->order_by(DB::expr('RAND()'))
			->limit(1)
			->find();
		
		// Détermination de l'heure de départ (chaque quart d'heure)
		$tps = 15*60;
        $base = floor( time() / $tps );
		$last = $base * $tps;
		$start = $last + $tps;
		
		$race 				= ORM::factory('race'); 
		$race->circuit_id 	= $circuit->id;
		$race->start 		= $start;
		
		$race->create();
	}

	/**
	 * Lance la simulation de la course
	 */
	public function simulate()
	{
		if ($this->loaded() AND $this->start < time() AND $this->chocobos->count_all() > 0)
		{
			Simulation::run($this);
			
			$this->reload();
		}
	}

	/**
	 * Supprime la course
	 * 
	 * @return void 
	 */
	public function delete()
	{
		foreach ($this->results->find_all() as $result) $result->delete();
		
		foreach ($this->waves->find_all() as $wave) $wave->delete();
		
		parent::delete();
	}

}
