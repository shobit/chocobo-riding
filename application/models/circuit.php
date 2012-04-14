<?php defined('SYSPATH') or die('No direct script access.');
 
class Circuit_Model extends ORM {
    
    protected $has_many = array('chocobos', 'results', 'waves');
    protected $has_one = array('location');
    
    public function display_race($format="code") 
    {
    	switch ($format) {
    		case 'code':
				$tab = array('entraînement', 'compétition', 'promenade');
    			$res = $tab[$this->race];
    			break;
    		case 'zone':
 		   		$tab = array(
 		   			Kohana::lang('circuit.index.training'), 
 		   			Kohana::lang('circuit.index.competition'), 
 		   			Kohana::lang('circuit.index.ride'));
 		   		$res = $tab[$this->race];
    			break;
    	}
    	return $res;
    }
	
	public function no_same_user($user_id) 
	{
		$nbr_same_chocobos = 
			ORM::factory('chocobo')
			   ->where('user_id', $user_id)
			   ->where('circuit_id', $this->id)
			   ->count_all();
		return ($nbr_same_chocobos == 0);
	}
    
    public static function create($class) 
	{
		for ($i=0; $i<=2; $i++) 
		{
			$circuits = 
				ORM::factory('circuit')
				   ->where('race', $i)
				   ->where('classe', $class)
				   ->where('status', 0)
				   ->where('finished', 0)
				   ->find_all();
			
			$res = true;
			
			foreach($circuits as $circuit)
			{
				if (count($circuit->chocobos) < 6) $res = false;
			}
				   
			if ($res) 
			{
				
				if ($class == 0)
					$location = ORM::factory('location')
						->where('classe', $class)
						->orderby(NULL, 'RAND()')
						->limit(1)->find();
				elseif ($class > 0)
					$location = ORM::factory('location')
						->where(array('classe<=' => $class, 'classe>=' => ($class-1)))
						->orderby(NULL, 'RAND()')
						->limit(1)->find();
					   
				$length = ($i<2) ? rand( ($class+$i+1)*9, ($class+$i+1)*10 ) : 0;
				$surface = rand(0, 6);
                
                $tps = array(15*60, 30*60, 60*60);
                $base = floor(time()/$tps[$i]);
				$last = ($base*$tps[$i]);
				$next = $last+$tps[$i];
				
				$circuit = ORM::factory('circuit');
				$circuit->race 			= $i; # 0 -> 2
				$circuit->surface 		= $surface; # 0 -> 7
				$circuit->location_id 	= $location->id;
				$circuit->classe 		= $class; # 0 -> 5
				$circuit->length 		= $length;
				$circuit->start 		= $next;
				$circuit->save();
				
			}
		}
	}
	
	public static function get_list($race, $class)
	{
		return ORM::factory('circuit')
			->where('race', $race)
			->where('classe', $class)
			->where('finished', 0)
			->find_all();
	}
	
	public function revise($simulate=true) 
	{
		$tps 			= $this->start - time();
		$nbr_chocobos 	= count($this->chocobos);
		$save			= false;
		
		if ( ($this->status == 0) and ($this->race == 1) and ($nbr_chocobos == 6) ) 
		{
			$this->status 	= 1;
			$save 			= true;
        }
        
        if ( ($this->status == 0 or $this->status == 1) and ($tps <= 0) ) 
        {
			$this->status 	= 2;
			if ($nbr_chocobos == 0) $this->finished = 1;
			$save 			= true;
		}
		
		elseif ( ($this->status == 2) and ($this->finished == 0) and $simulate) 
		{
			$this->db->query('START TRANSACTION;');
		  	$this->db->query("SELECT finished FROM cr_circuits WHERE id='".$this->id."' FOR UPDATE;");
		  	$this->db->update(
		   		'circuits', 
		   		array('finished' => 1),
		  		array('id' => $this->id)
		  	);
			$this->db->query('COMMIT;');
		  	$simulation = new Simulation;
			$simulation->run($this);
		}
		
		if ($save) $this->save();
		
		if ( ($this->status == 2) and ($this->finished == 1) ) 
		{
			$nbr_chocobos = ORM::factory('chocobo')
				->orwhere(array(
			  		'circuit_id'   => $this->id,
			  		'circuit_last' => $this->id))
				->count_all();
				
			if ($nbr_chocobos == 0) $this->delete();
		}
	}
	
	public function delete()
	{
		foreach ($this->chocobos as $chocobo) $chocobo->delete();
		foreach ($this->results as $result) $result->delete();
		foreach ($this->waves as $wave) $wave->delete();
		parent::delete();
	}

}
