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
	protected $race;
	
	public function run ( $race ) 
	{ 
		// ------
		//
		// ETAPE 1 : Initialisation des variables de classe
		//
		// ------
		$this->race = $race;
		
		$nbr_chocobos = 0;
		$chocobos = array();
		foreach ($race->chocobos as $chocobo)
		{
			$infos = $chocobo->as_array();
			
			// déclenchement de la rage
			$infos['has_rage'] = FALSE;
			if ($infos['rage'] == $chocobo->attr('rage_limit'))
			{
				$infos['has_rage'] = TRUE;
				$infos['speed'] += 5;
				$infos['intel'] += 5;
				$infos['endur'] += 5;
			}
			
			$infos['initiative'] = 100; #TODO
			$infos['box'] = $nbr_chocobos + 1; #TODO: mettre les box depuis l'inscription
			$infos['time'] = 0;
			$infos['distance_last'] = 0;
			$infos['distance_cumul'] = 0;
			$infos['allure'] = 'normal';
			$infos['course_last'] = 0;
			$infos['course_cumul'] = 0;
			$infos['course_min'] = 1; #vitesse de départ (FIXE)
			$infos['course_avg'] = (1 + $infos['speed'] / 2) / ($race->circuit->length / 600 + 1);	#vitesse moyenne de course
			$infos['course_max'] = 0;	#vitesse maximale que peut atteindre un chocobo
			$infos['arrived'] = FALSE; #token pour signaler l'arrivée du chocobo
			
			$chocobos[] = $infos;
			
			$script_chocobo[] = "{name:'" . $infos['name'] . "',colour:'" . $infos['colour'] . "'}";
			
			$nbr_chocobos++;
		}
		
		$script_chocobos = '[' . implode(',', $script_chocobo) . ']';
		
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
				
				$distance_current = min($course_current, $race->circuit->length - $chocobo['distance_cumul']);
				
				$chocobo['distance_last'] = $distance_current;
				$chocobo['distance_cumul'] += $distance_current;
				$chocobo['course_last'] = $course_current;
				if ($course_current > $chocobo['course_max']) { $chocobo['course_max'] = $course_current; }
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
				
				if ($chocobo['distance_cumul'] >= $race->circuit->length)
				{
					$nbr_chocobos_arrived ++;
					$position = Kohana::lang("race.pos$nbr_chocobos_arrived");
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
			$result->race_id = $race->id;
			$result->chocobo_id = $chocobo['id'];
			$result->name = $chocobo['name']; #mémorisation du nom du chocobo au cas où s'il n'existe plus après
			$result->box = $chocobo['box'];
			$result->position = $chocobo['position'];
			$result->time = $chocobo['time'];
			$result->course_avg = $chocobo['course_cumul'] / $chocobo['time'];
			$result->save();
			
			$c = ORM::factory('chocobo', $chocobo['id']);
			
			$c->race_id = 0;
			
			// nb de courses
			$c->nb_races ++;
			
			// vitesse record
			$c->set_max_speed($chocobo['course_max']);
			
			// rage
			if ($chocobo['has_rage'])
			{
				$c->rage = 0;
			}
			else
			{
				$c->set_rage($chocobo['position']);
			}
			
			// PL
			$c->pl -= $race->circuit->pl;
			
			// expérience
			if ($c->level < $c->lvl_limit)
			{
				$c->set_exp( ceil(($nbr_chocobos - $result->position + 1) * $race->circuit->xp/6) );
			}
			
			// gils
			$c->user->set_gils( ceil(($nbr_chocobos - $result->position + 1) * $race->circuit->gils/6) );
			$c->user->save();
			
			// côte
			if ($nbr_chocobos > 1)
			{
				$c->set_fame( ($chocobo['position'] <= floor($nbr_chocobos /2)) ? -0.01 : 0.01 );
			}
			
			$c->save();
		}
		
		$race->script = '{chocobos:' . $script_chocobos . ',tours:' . $script_tours  . '}';
		$race->save();
	}

}
