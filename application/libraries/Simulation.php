<?php 
// script de la course (array javascript)
/*

SCRIPT
	{
		chocobos: 
			[
				{
					name: string,
					colour: string
				},
				{
				
				}
			],
		tours:
			[
				{ TOUR 1
					distances: 
						{
							chocobo: string,
							allure: string,
							distance: string,
							course: string 
						}
					events:
						[
							{
								title: string
								allure: string
								content: string
							},
							{}
						]
				},
				{ TOUR 2
					
				} 
			]
	}


*/
class Simulation {
	
	// Informations sur la course (object php)
	protected $circuit;
	
	public function run ( $circuit ) 
	{ 
		// ------
		//
		// ETAPE 1 : Initialisation des variables de classe
		//
		// ------
		$this->circuit = $circuit;
		
		$nbr_chocobos = 0;
		$chocobos = array();
		foreach ($circuit->chocobos as $chocobo)
		{
			$infos = $chocobo->as_array();
			$infos['initiative'] = 100; 							#TODO
			$infos['box'] = $nbr_chocobos + 1; 						#TODO: mettre les box depuis l'inscription
			$infos['time'] = 0;										#
			$infos['distance_last'] = 0;							#
			$infos['distance_cumul'] = 0;							#
			$infos['allure'] = 'normal';							#allure de course
			$infos['course_last'] = 0;								#
			$infos['course_cumul'] = 0;								#
			$infos['course_min'] = 1;								#vitesse de départ (FIXE)
			$infos['course_avg'] = (1 + $infos['speed'] / 2) / 2;	#vitesse moyenne de course
			$infos['course_max'] = $infos['speed'] / 2;				#vitesse maximale que peut atteindre un chocobo
			$infos['arrived'] = FALSE; 								#token pour signaler l'arrivée du chocobo
				
			#ajouter le record course
			
			$chocobos[] = $infos;
			
			$script_chocobo[] = "{name:'" . $infos['name'] . "',colour:'" . $infos['colour'] . "'}";
			
			$nbr_chocobos++;
		}
		
		$script_chocobos = '[' . implode(',', $script_chocobo) . ']';
		
		/*foreach ($circuit->chocobos as $chocobo) 
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
		$circuit->reload();*/
		
		// ------
		//
		// ETAPE 2 : Simulation
		//
		// ------
		
		$tour = 0;
		
		$nbr_chocobos_arrived = 0;
		
		while ($nbr_chocobos_arrived < $nbr_chocobos) #tant que tous les chocobos ne sont pas arrivés
		{
			// nouveau tour	
			$tour++;
			
			// initialistion des scripts
			$script_point = array();
			$script_event = array();
			
			// utilisation des compétences & avancement du chocobo
			arr::order($chocobos, 'initiative', 'desc');
			foreach ($chocobos as &$chocobo)
			{
				if ($chocobo['arrived']) { continue; }
				
				// le chocobo utilise ou pas une de ses compétences
				//$chocobo->use_competence();
				
				// le chocobo met à jour son score d'initiative
				//$chocobo->update_initiative();
			
				// le chocobo avance
				if ($chocobo['allure'] == 'normal') 
				{
					if ($chocobo['course_last'] <= $chocobo['course_avg']) // Adjustable
					{
						$course_alea = rand(3 * $chocobo['speed'], 5 * $chocobo['speed']) /100;
						$course_current = $chocobo['course_last'] + $course_alea; // Adjustable
					}
					else
					{
						$course_alea = rand(3 * $chocobo['speed'], 5 * $chocobo['speed']) /100;
						$course_current = $chocobo['course_last'] - $course_alea; // Adjustable
					}
				}
				
				$distance_current = min($course_current, $circuit->length - $chocobo['distance_cumul']);
				
				$chocobo['distance_last'] = $distance_current;
				$chocobo['distance_cumul'] += $distance_current;
				$chocobo['course_last'] = $course_current;
				$chocobo['course_cumul'] += $course_current;
				$chocobo['time'] += $chocobo['distance_last'] / $chocobo['course_last'] * 1; // m/s
			}
			
			// on vérifie si le chocobo est à l'arrivée
			arr::order($chocobos, 'time', 'desc'); # l'ordre sert seulement si un des chocobos au moins est à l'arrivée
			foreach ($chocobos as &$chocobo)
			{
				if ($chocobo['arrived']) { continue; }
				
				$script_point[] = "{
					chocobo:'" . $chocobo['name'] . "',
					distance:'" . number_format($chocobo['distance_cumul'], 2, '.', '') . "',
					course:'" . number_format($chocobo['course_last'], 2, '.', '') . "',
					allure:'" . $chocobo['allure'] . "'
				}";
				
				if ($chocobo['distance_cumul'] >= $circuit->length)
				{
					$nbr_chocobos_arrived ++;
					$position = Kohana::lang("circuit.pos$nbr_chocobos_arrived");
					$script_event[] = "{chocobo:'" . $chocobo['name'] . "',title:'$position',allure:'happy'}";
					$chocobo['position'] = $nbr_chocobos_arrived;
					$chocobo['arrived'] = TRUE;
				}
			}
						
			$script_points = '[' . implode(',', $script_point) . ']';
			$script_events = '[' . implode(',', $script_event) . ']';
			$script_tour[] = '{points:' . $script_points . ',events:' . $script_events . '}';
		}
		
		$script_tours = '[' . implode(',', $script_tour) . ']';
		
		// ------
		//
		// ETAPE 3 : Enregistrement du script
		//
		// ------
		
		foreach ($chocobos as &$chocobo)
		{
			$result = ORM::factory('result');
			$result->circuit_id = $circuit->id;
			$result->chocobo_id = $chocobo['id'];
			$result->name = $chocobo['name']; #mémorisation du nom du chocobo au cas où s'il n'existe plus après
			$result->box = $chocobo['box'];
			$result->position = $chocobo['position'];
			$result->time = $chocobo['time'];
			$result->course_avg = $chocobo['course_cumul'] / $chocobo['time'];
			$result->save();
			
			$c = ORM::factory('chocobo', $chocobo['id']);
			
			$c->circuit_id = 0;
			
			if ($c->level < $c->lvl_limit)
			{
				$c->set_exp( ceil(($nbr_results - $result->position + 1) * 100/$nbr_results) );
			}
			
			$c->nb_races ++;
			
			//$c->set_rage($chocobo['rage']);
			
			if ($nbr_results > 1)
			{
				$c->set_fame( ($chocobo['position'] <= floor($nbr_results /2)) ? -0.01 : 0.01 );
			}
			
			$c->save();
		}
		
		$circuit->script = '{chocobos:' . $script_chocobos . ',tours:' . $script_tours  . '}';
		$circuit->save();
		
		/*
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
            
			// [ALL] Penality breath & Regains & RESULT{breath,hp,mp}
        	$apts = array('breath', 'hp', 'mp');
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
		}*/
		//$this->calcul_paris();
	}
	
	/**
	 * Reorder chocobo positions.
	 * 
	 * @access public
	 * @param mixed $circuit
	 * @return void
	 */
	/*public function order($circuit)
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
	}*/

}
