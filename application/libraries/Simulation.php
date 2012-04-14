<?php 
 
class Simulation {
	
	/**
	 * Simulate a race.
	 * 
	 * @access public
	 * @param mixed $circuit
	 * @return void
	 */
	public function run($circuit) 
	{ 
		// ------
		//
		// STEP 1 : initialization
		//
		// ------
		foreach ($circuit->chocobos as $chocobo) 
		{
			// creating result
			$result = ORM::factory('result');
			$result->circuit_id = $circuit->id;
			$result->chocobo_id = $chocobo->id;
			$result->save();
			
            // [ALL] average speed race
			$speed = $chocobo->attr('speed'); 
			$endur = $chocobo->attr('endur'); 
			$intel = $chocobo->attr('intel');
			$ls = $circuit->location->speed;
			$le = $circuit->location->endur;
			$li = $circuit->location->intel;
			$vit_th = ($ls*$speed +$le*$endur +$li*$intel) /($ls+$le+$li);
			$vit_mor = $vit_th + ($chocobo->moral - 50) /10;
			$avg_speed = rand($vit_th*100, $vit_mor*100) /100;
			
			// [!PROMENADE] rage 
			$rage_limit	= $chocobo->attr('rage_limit');
			if ($circuit->race <2 and $chocobo->rage >= $rage_limit)
			{
				$bonus = 2 + floor($rage_limit /20);
				$avg_speed += $bonus;
				$result->add_fact("rage", $bonus);
			}
			
			$result->avg_speed = $avg_speed;
            $result->save();
            
		}
		
		$this->order($circuit);
		$circuit->reload();
		
		// ------
		//
		// STEP 2 : High Job Competences
		//
		// ------
		/*foreach ($this->liste as $cr) {
			$this->cr = $cr;
			$job = launchJob($cr->getJob());
			$job->highJC();
		}*/
		
		// ------
		//
		// STEP 3 : Normal Job Competences
		//
		// ------
		/*foreach ($this->results as $tmp) {
			//$job = launchJob($cr->getJob());
			//$job->normalJC();
			//$this->wound();
		}
		$this->order();*/
		
		// ------
		//
		// STEP 4 : Low Job Competences
		//
		// ------
		/*$results = $this->get('results', false);
		$max = count($results);
		foreach ($results as $tmp) {
			$result = new Result();
			$result->find($tmp);
            
            // Circuit length on Breath
			
			
            //$this->cr = $cr;
			//$job = launchJob($cr->getJob());
			//$job->lowJC();
		}
		$this->order();*/
		
		// ------
		//
		// STEP 5 : Finalization
		//
		// ------
		$nbr_results = count($circuit->results);
		$lucky_items = 100;
		$results = ORM::factory('result')
			->where('circuit_id', $circuit->id)
			->orderby('position', 'asc')
			->find_all();
		foreach ($results as $result) 
		{
			$chocobo = $result->chocobo;
			
			// [!PROMENADE] speed record
			if ($circuit->race <2)
			{ 
				$avg_speed = $result->avg_speed;
				$max_speed = $chocobo->max_speed;
				if ($avg_speed > $max_speed) 
				{
					$result->add_fact("max_speed", $avg_speed, false);
					$chocobo->max_speed = $avg_speed;
					$chocobo->listen_success(array( # SUCCES
						"vitmax_25",
						"vitmax_50",
						"vitmax_100",
						"vitmax_150",
						"vitmax_175"
					));
				}
			}
			
			// [ALL] count races
			$races = array('trainings', 'compets', 'rides');
			$chocobo->{'nb_'.$races[$circuit->race]} ++;
            
            // [ALL] rage & RESULT{rage}
            $rage_limit	= max($chocobo->level, 10);
        	$gain_rage = ($circuit->race <2) ? $result->position : -$circuit->classe;
        	// TODO add_fact recompense rage
        	$result->rage += $gain_rage;
        	if ($circuit->race <2 and $chocobo->rage >= $rage_limit) 
        	{
        		$chocobo->rage = 0;
        	}
        	$rage = min($result->rage, $rage_limit - $chocobo->rage);
        	$rage = max($rage, -$chocobo->rage);
        	$result->rage = $rage;
        	$chocobo->rage += $rage;
        	
        	// [ALL] moral & RESULT{moral}
        	$gain_moral = $nbr_results - $result->position - floor($nbr_results /2);
        	// TODO add_fact recompense moral
        	$result->moral += $gain_moral;
        	$moral = min($result->moral, 100 - $chocobo->moral);
        	$moral = max($moral, -$chocobo->moral);
        	$result->moral = $moral;
        	$chocobo->moral += $moral; 
			
			// [TRAINING] xp & RESULT{xp}
			if ($circuit->race == 0 and $chocobo->level < $chocobo->lvl_limit) 
			{
				$gain_xp 	 = ceil( ($nbr_results - $result->position + 1) * 100/6);
				// TODO add_fact recompense xp
				$bonus_xp 	 = $chocobo->attr('bonus_xp');
				// TODO add_fact bonus_xp
				$gain_xp 	 = ceil($gain_xp *($bonus_xp /100 +1));
				$result->xp += $gain_xp;
				// MIN ? MAX ? 
				$result->add_fact("xp_total", $gain_xp);
				$stats = $chocobo->evolve($result->xp);
				if ($stats['nb_levels'] >0) 
				{
					$result->add_fact('level', $stats['nb_levels'].'/'.$stats['level']);
				}
				if ($stats['nb_classes'] >0) 
				{
					$result->add_fact('classe', $stats['nb_classes'].'/'.$stats['classe']);
				}
			}
            
            // [COMPETITION - !ALONE] fame & RESULT{fame}
            if ($circuit->race == 1 and $nbr_results >1) 
            {
				$gain_fame 		 = ($result->position <= floor($nbr_results /2)) ? -0.01 : 0.01;
				// TODO add_fact recompense fame
				$result->fame 	+= $gain_fame;
				$fame 			 = min($result->fame, 1 - $chocobo->fame);
				$fame 			 = max($fame, 0.01 - $chocobo->fame);
				$result->fame	 = $fame;
				$chocobo->fame 	+= $fame;
				$chocobo->listen_success(array( # SUCCES
					"fame_075",
					"fame_050",
	    			"fame_025",
	    			"fame_001"
	    		));
			}
			
			// [COMPETITION] gils & RESULT{gils}
			if ($circuit->race == 1)
			{
            	$gain_gils = $result->gils +$circuit->classe *5 +($nbr_results - $result->position +1);
            	// TODO add_fact recompense gils
            	$bonus_gils = $chocobo->attr('bonus_gils');
            	$gils = ceil($gain_gils *($bonus_gils /100 +1));
            	// TODO add_fact bonus_gils
            	$result->gils += $gils;
            	$result->add_fact("gils_total", $gils);
            	$chocobo->user->gils += $result->gils;
            	$chocobo->user->listen_success(array( # SUCCES
					"gils_500",
					"gils_1000",
					"gils_5000",
					"gils_10000"
				));
            	$chocobo->user->save();
            }
            
			// [ALL] Penality breath & Regains & RESULT{breath,energy,spirit}
        	$apts = array('breath', 'energy', 'spirit');
            foreach($apts as $apt)
            {
            	${$apt.'_limit'} = $chocobo->attr($apt.'_limit');
            	if ($circuit->race == 2)
            	{
            		${$apt.'_recup'} = $chocobo->attr($apt.'_recup');
            		$coeff = ${$apt.'_recup'};
            		// TODO add_fact *_recup
            		${'gain_'.$apt} = $coeff /100 *${$apt.'_limit'};
            		// TODO add_fact regains
            	}
            	elseif ($apt == "breath") 
            	{
            		${'gain_'.$apt} = 0 -$circuit->length;
            		// TODO add_act penality breath
            	}
            	else ${'gain_'.$apt} = 0;
            	$result->$apt  += ${'gain_'.$apt};
            	${'gain_'.$apt} = min($result->$apt, ${$apt.'_limit'} - $chocobo->$apt);
            	${'gain_'.$apt} = max(0 -$chocobo->$apt, $result->$apt);
            	$result->$apt   = ${'gain_'.$apt};
            	$chocobo->$apt += ${'gain_'.$apt}; 
            }
            
            // [COMPETITION] rewards
            if ($circuit->race == 1 and $result->position <= floor($nbr_results /2)) 
            {
           		$classe = $circuit->classe; // 0 - 5
            	$windfall = $chocobo->attr('windfall');
                $chance = $classe *8 + $windfall;
                $chance = min($chance, $lucky_items);
                
                $items = array('vegetable', 'nut', 'equipment');
                $type = $items[rand(0, 2)];
                $type = "vegetable";
                $item = ORM::factory($type);
                $item->generate($chance, $chocobo->user);
                $lucky_items = $item->price;
                
                $result->add_fact("reward".$result->position, $type."/".$item->id);
            }
            
            // [ALL] finalizing
            // status free except if injured
			$chocobo->status = ($chocobo->status != 2) ? 0 : 2;
			// last circuit revision
            $last_circuit = ORM::factory('circuit', $chocobo->circuit_last);
			$chocobo->circuit_last = $circuit->id;
			$chocobo->circuit_id = null;
			$result->save();
			$chocobo->save();
			
			// updating last circuit
			if ($last_circuit->id > 0) 
			{
				$last_circuit->revise();
			}
		}
		//$this->calcul_paris();
	}
	
	/**
	 * Reorder chocobo positions.
	 * 
	 * @access public
	 * @param mixed $circuit
	 * @return void
	 */
	public function order($circuit)
	{
		$position = 1;
		$results = ORM::factory('result')
			->where('circuit_id', $circuit->id)
			->orderby('avg_speed', 'desc')
			->find_all();
		foreach ($results as $result) 
		{
			$result->position = $position;
			$result->save();
			$position++;
		}
	}

}
