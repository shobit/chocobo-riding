<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Chocobo extends ORM { 
    
    protected $_belongs_to = array('user' => array(), 'race' => array());
    protected $_has_many = array('results' => array(), 'equipment' => array());
    
    /**
     * Retourne tous les chocobos pour la vue des chocobos
     */
    public static function get_chocobos()
    {
    	return ORM::factory('chocobo')
			->join('users', 'LEFT')
			->on('users.id', '=', 'chocobo.id')
			->where('chocobo.name', '!=', '')
			->where('users.banned', '=', 0)
			->where('users.deleted', '=', 0)
			->find_all();
    }

    /**
     * Rècupère l'historique des courses
     * non supprimés, décroissant
     */
    public function get_results()
    {
    	return $this->results
			->where('deleted', '=', FALSE)
			->order_by('id', 'desc')
			->find_all();
    }
    
    public function display_colour($format) {
    	$formats = array(
    		'code' => array('yellow', 'red', 'green', 'blue', 'black', 'silver', 'white', 'gold'),
    		'zone' => array('jaune', 'Rouge', 'Vert', 'Bleu', 'Noir', 'Argent', 'Blanc', 'Or')
    	);
    	return $formats[$format][$this->colour];
    }
    
    public function display_classe() {
    	$classes = array('C', 'B', 'A', 'A+', 'S', 'S+');
    	return $classes[$this->classe];
    }
    
    public function display_job($format) {
    	$formats = array(
    		'code' => array(
    			"chocobo", "knight", "whitemage", 
    			"blackmage", "thief", "scholar", 
    			"ninja", "darkknight", "dragoon"),
    		'zone' => array(
    			"Chocobo", "Soldat", "Mage blanc", 
    			"Mage noir", "Voleur", "Mathématicien", 
    			"Ninja", "Chevalier noir", "Chevalier dragon")
    	);
    	return $formats[$format][$this->job];
    }
    
    public function display_status() {
    	$res = "";
    	if ($this->race_id == 0)
    	{
    		$res = 'Libre';
    	}
    	else
    	{
    		$res = html::anchor('races/' . $this->race_id, 'En course');
    	}
    	return $res;
    }
    
    public function display_gender($format) {
    	$formats = array(
    		'code' => array('', 'female', 'male'),
    		'zone' => array('', 'Féminin', 'Masculin')
    	);
    	return $formats[$format][$this->gender];
    }
    
    public function display_fame() {
    	return number_format($this->fame, 2, '.', '');
    }
    
    public function get_price() {
    	$res = 0;
    	$res += $this->level *2;
    	$res += (100-$this->fame*100+1) *2;
    	$res *= $this->get_category() +1;
    	if ($this->level==100 
    		and $this->fame==0.01 
    		and $this->get_category()==3) 
    			$res = 7777;
    	return ceil($res);
    }
    
    public function get_category()
    {
    	$res = 0;
    	if (in_array($this->colour, array(1, 2, 3))) $res = 1;
    	elseif (in_array($this->colour, array(4, 5, 6))) $res = 2;
    	if ($this->colour == 7) $res = 3;
    	return $res;
    }
    
    /**
     * Get a caracteristic of the chocobo
     * 
     * @access public
     * @param mixed $c	codename of caracteristic
     * @return void		Array (total, base, bonus, aptitudes, equipment, job, clan)
     * 
     */
    public function attr($c, $output_table=false) {
		$res = array();
		
		// init
		$base = 0;
		$coeff = 1;
		$linked = null;
		
		// Base
		switch ($c) 
		{	
			case 'speed': $base = floor($this->speed); break;
			case 'endur': $base = floor($this->endur); break;
			case 'intel': $base = floor($this->intel); break;
			
			// SPEED
			case 'pl_limit': 	$base = $this->attr('endur') *2; 	break;	
			case 'pl_up': 		$base = 0.2; 						break;	
			case 'pl_recup': 	$base = 30; 						break;
				
			// ENDUR
			case 'hp_limit': 	$base = $this->attr('endur') *3; 	break;	
			case 'hp_up': 		$base = 0.2; 						break;	
			case 'hp_recup': 	$base = 30; 						break;
			
			// INTEL
			case 'mp_limit': 	$base = ceil($this->attr('intel') /3); 	break;	
			case 'mp_up': 		$base = 0.2; 						break;	
			case 'mp_recup': 	$base = 30; 						break;
			
			// ALL
			case 'bonus_gils': 	$base = 0; break;
			case 'bonus_xp': 	$base = 0; break;
			case 'bonus_items': $base = 0; break;
			
			// returned values
			case 'rage_limit': 		return max($this->level, 10);
			case 'pc_limit': 		return ceil($this->attr('intel') /2); 
			
			// Master_* & resistance	
		}
		
		$res['base'] = $base;
		$bonus = 0;
		
		// TODO adding clan bonus (max: 10)
		
		// TODO adding job bonus (max: 5)
		
		// adding equipment bonus
		$bonus_equip = 0;
		
		foreach($this->equipment->find_all() as $equip) 
		{
			foreach($equip->equipment_effects->find_all() as $effect)
			{	
				if ($effect->name == $c) 
				{
					$bonus_equip += $effect->value;
				}
			}

			if ($c == 'resistance') { $bonus_equip += $equip->resistance; }
		}
		
		$res['equipment'] = $bonus_equip;
		$bonus += $bonus_equip;
		
		// bonus & total
		$res['bonus'] = $bonus;
		$res['total'] = $base + $bonus;
		
		// output format
		if ($output_table)
		{
			return ($res);
		}
		else
		{
			return ($res['total']);
		}
	}
	
	/**
	 * Regain des caractéristiques pl / hp / moral
	 * 
	 * @access public
	 * @return void
	 */
	public function regain() 
	{
		//$res = "";
		$apts = array("pl", "hp", "mp");
		$minutes = floor( (time() -$this->moved) /60 );
		if ($minutes >= 1) 
		{
			foreach ($apts as $apt) 
			{
				//$start 	 = floor($this->$apt);
				$limit   	 = $this->attr($apt."_limit");
				$up 		 = $this->attr($apt."_up");
				$gain		 = round($limit *$up /100, 2);
				$value		 = $minutes *$gain;
				$this->$apt += $value;
				$this->$apt  = min($this->$apt, $limit);
				//$end 		 = floor($this->$apt);
				//$res[$apt] = $end -$start;
			}
		}
		$this->moved += $minutes *60;
		$this->save();
		//return $res;
	}
	
	public function baby_status()
	{
		$jgrowl = "";
		if (time() > $this->birthday+3600)
		{
			$this->status = 0;
			$this->save();
			$jgrowl = $this->name." est devenu <b>adulte</b>!";
		}
		return $jgrowl;
	}
	
	/**
	 * Modifie les PL du chocobo
	 *
	 * @param int $pl
	 */
	public function set_pl($pl)
	{
		$pl_min = 0;
		$pl_max = $this->attr('pl_limit');
		$pl = $this->pl + $pl;
		$pl = max($pl_min, $pl);
		$pl = min($pl_max, $pl);
		$this->pl = $pl;
	}

	/**
	 * Modifie les HP du chocobo
	 *
	 * @param int $hp
	 */
	public function set_hp($hp)
	{
		$hp_min = 0;
		$hp_max = $this->attr('hp_limit');
		$hp = $this->hp + $hp;
		$hp = max($hp_min, $hp);
		$hp = min($hp_max, $hp);
		$this->hp = $hp;
	}

	/**
	 * Modifie les MP du chocobo
	 *
	 * @param int $mp
	 */
	public function set_mp($mp)
	{
		$mp_min = 0;
		$mp_max = $this->attr('mp_limit');
		$mp = $this->mp + $mp;
		$mp = max($mp_min, $mp);
		$mp = min($mp_max, $mp);
		$this->mp = $mp;
	}

	/**
	 * Modifie l'expérience du chocobo
	 *
	 * @param int $gain XP gagné
	 */
	public function set_exp($gain) 
	{
		$res = array(
			'level' 		=> 0,
			'classe' 		=> 0, 
			'nb_levels' 	=> 0, 
			'nb_classes' 	=> 0,
			'nb_points'		=> 0,
			'speed'			=> 0, 
			'endur'			=> 0, 
			'intel'			=> 0
		);
		$xp = $this->xp + $gain;
		
		while ($xp >= 100 and $this->level < $this->lvl_limit) #montée d'un niveau
		{
			$xp -= 100;
			$this->level ++;
			$res['nb_levels'] ++;
			$this->points += 2;
			$res['nb_points'] += 2;
			$this->listen_success(array( # SUCCES
				"level_10",
				"level_20",
				"level_30",
				"level_40",
				"level_50",
				"level_60",
				"level_70",
				"level_80",
				"level_90",
				"level_100"
			)); 
			
			$new_classe = FALSE;
			if ($this->classe == 0 and $this->level >= 10) $new_classe = TRUE;
			if ($this->classe == 1 and $this->level >= 30) $new_classe = TRUE;
			if ($this->classe == 2 and $this->level >= 50) $new_classe = TRUE;
			if ($this->classe == 3 and $this->level >= 70) $new_classe = TRUE;
			if ($this->classe == 4 and $this->level >= 90) $new_classe = TRUE;
			if ($new_classe) #montée d'une classe
			{
				$this->classe ++;
				$res['nb_classes'] ++;
				
				$this->listen_success(array( #SUCCES
	        		"classe_b", 
	        		"classe_a",
	        		"classe_aplus",
	        		"classe_s",
	        		"classe_splus"
	        	));
				
				$colours = array (
					0 => array(10, 10, 10),
					1 => array(30, 5, 5),
					2 => array(5, 30, 5),
					3 => array(5, 5, 30),
					4 => array(25, 25, 0),
					5 => array(0, 25, 25),
					6 => array(25, 0, 25),
					7 => array(20, 20, 20),
				);
				
				$speed = $colours[$this->colour][0]/5;
				$endur = $colours[$this->colour][1]/5;
				$intel = $colours[$this->colour][2]/5;
				if ($speed > 0) 
				{
					$this->speed += $speed;
					$res['speed'] += $speed;
				}	
				if ($intel > 0)
				{ 
					$this->intel += $endur;
					$res['intel'] += $intel;
				}
				if ($endur > 0)
				{ 
					$this->endur += $intel;
					$res['endur'] += $endur;
				}
			}
		}
		
		$this->xp 		= $xp;
		$res['level'] 	= $this->level;
		$res['classe'] 	= $this->classe;
		return $res;
	}
	
	/**
	 * Modifie la rage du chocobo
	 *
	 * @param int $rage
	 */
	public function set_rage($rage)
	{
		$rage_min = 0;
		$rage_max = $this->attr('rage_limit');
		$rage = $this->rage + $rage;
		$rage = min($rage_max, $rage);
		$rage = max($rage_min, $rage);
		$this->rage = $rage;
	}
	
	/**
	 * Modifie la côte du chocobo
	 *
	 * @param float $fame
	 */
	public function set_fame($fame)
	{
		$fame_min = 0.01;
		$fame_max = 1;
		$fame = $this->fame + $fame;
		$fame = min($fame_max, $fame);
		$fame = max($fame_min, $fame);
		$this->fame = $fame;
		$this->listen_success(array( # SUCCES
			"fame_075",
			"fame_050",
			"fame_025",
			"fame_001"
		));
	}
	
	/**
	 * Modifie la vitesse record du chocobo
	 *
	 * @param float $max_speed
	 */
	public function set_max_speed($max_speed)
	{
		if ($max_speed > $this->max_speed)
		{
			$this->max_speed = $max_speed;
			$this->listen_success(array( # SUCCES
				"vitmax_25",
				"vitmax_50",
				"vitmax_100",
				"vitmax_150",
				"vitmax_175"
			));
		}
	}

	/**
	 * Augmente une des aptitudes du chocobo
	 * 
	 * @param $apt string (speed|endur|intel)
	 * @return void 
	 */
	public function boost($apt)
	{
		$apts = array('speed', 'endur', 'intel');
		
		if (in_array($apt, $apts) and $this->points > 0) 
		{
			$this->$apt++;
			$this->points--;
			$this->update();
		}
	}

	public function vignette() 
	{
		$content = '';
		$content .= 'Niveau : ' . ' ' . $this->level . ' /' . $this->lvl_limit . '<br />';
		$content .= 'Vitesse : ' . ' ' . $this->attr('speed') . '<br />';
		$content .= 'Intelligence : ' . ' ' . $this->attr('intel') . '<br />';
		$content .= 'Endurance : ' . ' ' . $this->attr('endur') . '<br />';
		
		return Vignette::display($this->name, $content);
	}

	/*
	 * génère le lien pour accéder au profil du chocobo
	 */
	public function link ()
	{
		return html::anchor('chocobos/' . $this->id, $this->name);
	}

	/**
	 * Génère un chocobo de chaque genre de $level pour la boutique
	 * 
	 * @param $level int Niveau des chocobos
	 * @return object
	 */
	public static function get_for_shop($level)
	{
		$v = ORM::factory('chocobo')
	    	->where('user_id', '=', 0)
	    	->where('lvl_limit', '=', $level);
		
		for ($i = 1; $i <= 2; $i++)
		{
			$x = clone $v;
			$count = $x->where('gender', '=', $i)->count_all();

			if ($count == 0)
			{
				$nut = ORM::factory('nut');
				$nut->gender = $i;
				ORM::factory('chocobo')->generate(0, $nut, $level-1);
			}
		}

		return $v->find_all();
	}
	
	/**
	 * Génère un nouveau chocobo à l'aide d'une noix
	 *
	 * @param int $user_id
	 * @param object $nut
	 * @param int $level
	 */
	public function generate($user_id, $nut, $level=16)
	{
		if ($nut != NULL) 
		{
			$this->user_id 		= $user_id;
        	$this->level 		= 1;
        	$this->fame 		= 1;
       	
       		$this->lvl_limit 	= $level + $nut->get_effect('lvl_limit') +1;
        	$this->gender 		= $nut->gender;
        	$this->colour 		= $nut->choose_color();
        	$this->listen_success(array( #SUCCES
        		"chocobo_red", 
        		"chocobo_blue",
        		"chocobo_green",
        		"chocobo_black",
        		"chocobo_silver",
        		"chocobo_white",
        		"chocobo_gold"
        	));
        	
        	$this->job 			= $nut->choose_job();
        	// Ajouter succès jobs ici
        
        	$this->speed 		= 20 + $nut->get_effect('speed');
        	$this->intel 		= 20 + $nut->get_effect('intel');
        	$this->endur 		= 20 + $nut->get_effect('endur');
        	
        	$this->pl	 		= $this->endur *2;
        	$this->hp 			= $this->endur *3;
        	$this->mp 			= $this->intel *1;
        	
        	$this->birthday 	= time();
        	$this->save();
        	
        	return $this;
        }
	}

	/**
	 * Achète un chocobo pour un utilisateur
	 *
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function buy($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Ce chocobo n'existe pas.");
		}

		if ($this->user_id != 0)
		{
			$msg = __("Ce chocobo n'est pas à vendre.");
		}

		if ($user->gils < $this->get_price())
		{
			$msg = __("Vous n'avez pas assez d'argent.");
		}

		if ($user->chocobos->count_all() >= $user->get_boxes())
		{
			$msg = __("Vous n'avez plus de box libre.");
		}

		if ( ! isset($msg))
		{
			$this->user_id = $user->id;
			$this->update();
			
			$user->set_gils(-$this->get_price());
			$user->update();

			$msg = __('Chocobo acheté !');
		}

		return $msg;
	}

	/**
	 * Vend un chocobo pour le joueur en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function sale($user)
	{
		if ($this->loaded() === FALSE) 
		{
			$msg = __("Ce chocobo n'existe pas.");
		}
		
		if ($this->user_id != $user->id)
		{
			$msg = __("Ce chocobo ne vous appartient pas.");
		}
		
		if ($user->chocobos->count_all() == 1)
		{
			$msg = __("Il ne vous reste plus que ce chocobo.");
		}
		
		if ( ! isset($msg)) 
		{
			$user->set_gils($this->get_price());
			$user->nbr_chocobos_saled++;
			$user->update();
			
			$this->delete();
			$msg = __('Chocobo vendu !');
		}

		return $msg;
	}

	/**
	 * Retourne vrai si le chocobo est apte à s'inscrire à la course
	 * 
	 * @param $race object
	 */
	public function _register($race)
	{
		$msg = '';
		
		if ($race->loaded() === FALSE)
		{
			$msg = __("La course n'a pas été trouvée !");
		}
		
		if ($race->start <= time())
		{
			$msg = __('La course est déjà commencée.');
		}
		
		if ($this->classe !== $race->circuit->classe)
		{
			$msg = __("Votre chocobo n'a pas la classe requise.");
		}
		
		if ($race->chocobos->count_all() >= 6)
		{
			$msg = __('La course est pleine.');
		}
		
		if ( ! empty($this->race_id))
		{
			$msg = __('Votre chocobo est inscrit à une autre course.');
		}
		
		if ($this->pl < $race->circuit->pl)
		{
			$msg = __("Votre chocobo n'a plus d'énergie pour courir.");
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}

	/**
	 * Retourne vrai si le chocobo est apte à se désinscrire de la course
	 * 
	 * @param $race object
	 */
	public function _unregister($race)
	{
		$msg = '';
		
		if ($race->loaded() === FALSE)
		{
			$msg = __("La course n'a pas été trouvée !");
		}
		
		if ($race->start <= time())
		{
			$msg = __('La course est déjà commencée.');
		}
		
		if ($this->race_id !== $race->id)
		{
			$msg = __("Votre chocobo n'est pas inscrit à cette course.");
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}

	/**
	 * Inscris le chocobo à la course
	 * 
	 * @param $race object
	 */
	public function register($race)
	{
		$r = $this->_register($race);
		
		if ($r['success'] === TRUE)
		{
			$this->race_id = $race->id;
			$this->update();
		}

		return $r;
	}

	/**
	 * Désinscrit le chocobo de la course
	 * 
	 * @param $race object
	 */
	public function unregister($race)
	{
		$r = $this->_unregister($race);
		
		if ($r['success'] === TRUE)
		{
			$this->race_id = 0;
			$this->update();
		}

		return $r;
	}
	
	// listenning for successes
	// $chocobo->listen_success("001");
    public function listen_success($refs)
    {
    	if (! is_array($refs)) $refs = array($refs);
	    
	    foreach($refs as $ref)
	    {
	    	$res = false;
	    	switch ($ref)
	    	{
	    		case "classe_b": if ($this->classe >= 1) $res = TRUE; break;
	    		case "classe_a": if ($this->classe >= 2) $res = TRUE; break;
	    		case "classe_aplus": if ($this->classe >= 3) $res = TRUE; break;
	    		case "classe_s": if ($this->classe >= 4) $res = TRUE; break;
	    		case "classe_splus": if ($this->classe >= 5) $res = TRUE; break;
	    		case "chocobo_red": if ($this->colour == 1) $res = TRUE; break;
	    		case "chocobo_blue": if ($this->colour == 2) $res = TRUE; break;
	    		case "chocobo_green": if ($this->colour == 3) $res = TRUE; break;
	    		case "chocobo_black": if ($this->colour == 4) $res = TRUE; break;
	    		case "chocobo_silver": if ($this->colour == 5) $res = TRUE; break;
	    		case "chocobo_white": if ($this->colour == 6) $res = TRUE; break;
	    		case "chocobo_gold": if ($this->colour == 7) $res = TRUE; break;
	    		case "level_10": if ($this->level >= 10) $res = TRUE; break;
	    		case "level_20": if ($this->level >= 20) $res = TRUE; break;
	    		case "level_30": if ($this->level >= 30) $res = TRUE; break;
	    		case "level_40": if ($this->level >= 40) $res = TRUE; break;
	    		case "level_50": if ($this->level >= 50) $res = TRUE; break;
	    		case "level_60": if ($this->level >= 60) $res = TRUE; break;
	    		case "level_70": if ($this->level >= 70) $res = TRUE; break;
	    		case "level_80": if ($this->level >= 80) $res = TRUE; break;
	    		case "level_90": if ($this->level >= 90) $res = TRUE; break;
	    		case "level_100": if ($this->level >= 100) $res = TRUE; break;
	    		case "fame_075": if ($this->fame <= 0.75) $res = TRUE; break;
	    		case "fame_050": if ($this->fame <= 0.50) $res = TRUE; break;
	    		case "fame_025": if ($this->fame <= 0.25) $res = TRUE; break;
	    		case "fame_001": if ($this->fame <= 0.01) $res = TRUE; break;
	    		case "vitmax_25": if ($this->max_speed >= 25) $res = TRUE; break;
	    		case "vitmax_50": if ($this->max_speed >= 50) $res = TRUE; break;
	    		case "vitmax_100": if ($this->max_speed >= 100) $res = TRUE; break;
	    		case "vitmax_150": if ($this->max_speed >= 150) $res = TRUE; break;
	    		case "vitmax_175": if ($this->max_speed >= 175) $res = TRUE; break;
	    	}
	    	$title = ORM::factory('title')->where('name', '=', $ref)->find();
	    	if ($res and ! $this->user->success_exists($title->id)) $this->user->success_add($title->id);
	    }
    }

    /**
     * Modifier le nom de chocobo
     */
    public function update_name($post)
    {
    	$this->name = $post['name'];
		$this->update();
	}
    
    /**
     * Supprime le chocobo
     *
     * @return void 
     */
    public function delete()
    {
    	foreach ($this->results->find_all() as $result) $result->to_delete();
    	
    	parent::delete();
    }
 
}
