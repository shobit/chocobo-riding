<?php defined('SYSPATH') or die('No direct script access.');
 
class Chocobo_Model extends ORM { 
    
    protected $belongs_to = array('user', 'race');
    protected $has_many = array('results', 'equipment');
    
    public function image($format) {
    	if ($format == "mini") 
    		$url = "images/chocobos/".gen::colour($this->colour)."/generic.gif";
    	elseif ($format == "menu") 
    	{
    		if ($this->status == 2)
    			$url = "images/chocobos/".gen::colour($this->colour)."/baby/menu.png";
    		else
	    		$url = "images/chocobos/".gen::colour($this->colour)."/".
    				$this->display_job('code')."/".$format.".png";
    	}
    	return html::image($url);
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
    	switch($this->status) 
    	{
    		case 0:
    			$res = "Libre";
    			break;
    		case 2:
    			$res = "Bébé";
    			break;
    		case 3:
    			$res = html::anchor('races/'.$this->race_id, "En course");
    			break;
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
			case 'mp_limit': 	$base = $this->attr('intel') *1; 	break;	
			case 'mp_up': 		$base = 0.2; 						break;	
			case 'mp_recup': 	$base = 30; 						break;
			
			// ALL
			case 'bonus_gils': 	$base = 0; break;
			case 'bonus_xp': 	$base = 0; break;
			
			// returned values
			case 'rage_limit': 		return max($this->level, 10);
			case 'moral_limit': 	return 100;
			case 'pc_limit': 		return ceil($this->attr('intel') /2); 
			
			// Master_* & resistance	
		}
		
		$res['base'] = $base;
		$bonus = 0;
		
		// TODO adding clan bonus (max: 10)
		
		// TODO adding job bonus (max: 5)
		
		// adding equipment bonus
		$bonus_equip = 0;
		
		foreach($this->equipment as $equip) 
		{
			foreach($equip->effects as $effect)
			{	
				if ($effect->name == $c) 
				{
					$bonus_equip += $effect->value;
				}
			}
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
	 * (void) modifie l'expérience du chocobo
	 *
	 * (int) $gain
	 */
	public function set_exp ( $gain ) 
	{
		$res 	 = array(
			'level' 		=> 0,
			'classe' 		=> 0, 
			'nb_levels' 	=> 0, 
			'nb_classes' 	=> 0,
			'nb_points'		=> 0,
			'speed'			=> 0, 
			'endur'			=> 0, 
			'intel'			=> 0);
		$xp  	 = $this->xp + $gain;
		
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
			
			$new_classe = false;
			if ($this->classe == 0 and $this->level >= 10) $new_classe = true;
			if ($this->classe == 1 and $this->level >= 30) $new_classe = true;
			if ($this->classe == 2 and $this->level >= 50) $new_classe = true;
			if ($this->classe == 3 and $this->level >= 70) $new_classe = true;
			if ($this->classe == 4 and $this->level >= 90) $new_classe = true;
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
	 * (void) modifie la rage du chocobo
	 *
	 * (int) $rage
	 */
	public function set_rage ( $rage )
	{
		$rage_limit	= $this->attr('rage_limit');
		$rage = $this->rage + $rage;
		$rage = min($rage, $rage_limit);
		$rage = max($rage, 0);
		$this->rage = $rage;
	}
	
	/**
	 * (void) modifie la côte du chocobo
	 *
	 * (float) $fame
	 */
	public function set_fame ( $fame )
	{
		$fame = $this->fame + $fame;
		$fame = min($fame, 1);
		$fame = max($fame, 0.01);
		$this->fame = $fame;
		$this->listen_success(array( # SUCCES
			"fame_075",
			"fame_050",
			"fame_025",
			"fame_001"
		));
	}
	
	/**
	 * (void) modifie la vitesse record du chocobo
	 *
	 * (float) $max_speed
	 */
	public function set_max_speed ( $max_speed )
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
	
	// TODO FUNC: Informations du chocobo en popup (HTML)
	public function vignette() 
	{
		$speed = $this->attr('speed');
		$intel = $this->attr('intel');
		$endur = $this->attr('endur');
		
		$res  = "";
		$res .= html::anchor('chocobo/view/'.$this->name, $this->name, array('id'=>'a'.$this->id.'_r'));
		$res .= '<div id="tip'.$this->id.'_r" style="display:none;">
			<b>'.$this->name.'</b> ('.$this->user->username.')<br />
			<small>Niveau '.$this->level.', Côte '.$this->display_fame().'<br />
			Vit: '.$speed.' / Int: '.$intel.' / End: '.$endur.'</small>
		</div>
		<script type="text/javascript">
			$(window).bind(\'load\', function() {
				$(\'#a'.$this->id.'_r\').bubbletip($(\'#tip'.$this->id.'_r\'), {
					deltaDirection: \'right\',
					offsetLeft: 20
				});
			});
		</script>';
		
		return $res;
	}
	
	// adding a new chocobo into DB
	public static function add_one($user_id, $nut, $level=16)
	{
		if ($user_id!=0 and $nut!=null) 
		{
			$chocobo = ORM::factory('chocobo');
			$chocobo->user_id = $user_id;
			$chocobo->status	= ($nut->level == 0) ? 0 : 2; #bébé
        	$chocobo->level 	= 1;
        	$chocobo->fame 		= 1;
       	
       		$chocobo->lvl_limit = $level+$nut->level;
        	$chocobo->gender 	= $nut->gender;
        	$chocobo->colour 	= $nut->choose_colour(); #renvoie le numéro de couleur
        	$chocobo->listen_success(array( #SUCCES
        		"chocobo_red", 
        		"chocobo_blue",
        		"chocobo_green",
        		"chocobo_black",
        		"chocobo_silver",
        		"chocobo_white",
        		"chocobo_gold"
        	));
        	
        	$chocobo->job 		= $nut->choose_job($chocobo->lvl_limit);
        	// Ajouter succès jobs ici
        
        	$chocobo->speed 	= 20+$nut->speed;
        	$chocobo->intel 	= 20+$nut->intel;
        	$chocobo->endur 	= 20+$nut->endur;
        	
        	$chocobo->pl	 	= 60;
        	$chocobo->hp 		= 3*$chocobo->intel;
        	$chocobo->mp 		= 2*$chocobo->endur;
        	
        	$chocobo->moral		= 50;
        	
        	$chocobo->birthday 	= time();
        	$chocobo->moved 	= time()+3600; #durée de 1h pour le mode bébé
        	$chocobo->save();
        	return $chocobo;
        }
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
	    	$title = ORM::factory('title')->where('name', $ref)->find();
	    	if ($res and ! $this->user->success_exists($title->id)) $this->user->success_add($title->id);
	    }
    }
    
    /**
	 * Met à jour la course actuelle du chocobo (+notification)
	 *
	 */
	public function update_race ()
	{
		// repère si le chocobo du joueur est inscrit à une course qui a déjà commencé
		$race = ORM::factory('race', $this->race_id);
		if ($race->loaded and $race->start < time())
		{
			// SIMULATION
			$s = new Simulation();
			$s->run($race);
		}
		
		// repère si le chocobo possède un historique de course non vu et non notifié
		// Note : ne peut pas en avoir plusieurs
		$result = ORM::factory('result')
			->where('chocobo_id', $this->id)
			->where('seen', FALSE)
			->where('notified', FALSE)
			->find();
		
		if ($result->loaded) 
		{	
			$result->notified = TRUE;
			$result->save();
			/*if (Router::$current_uri != 'races/' . $result->race_id) 
			{
				jgrowl::add(html::anchor('races/' . $result->race_id, 'Course terminée !', array('class' => 'jgrowl')));
			}*/
		}
	}
    
    /**
     * supprime le chocobo
     *
     */
    public function delete ()
    {
    	foreach ($this->results as $result) $result->to_delete();
    	
    	parent::delete();
    }
 
}
