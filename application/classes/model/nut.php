<?php 

class Model_Nut extends ORM 
{
	protected $_belongs_to = array('user' => array());
	protected $_has_many = array('nut_effects' => array());
	
    var $colors = NULL;
    var $jobs = NULL;

    /**
	 * Génère $nbr noix de $level pour la boutique
	 * 
	 * @param $level int Niveau de l'objet
	 * @param $nbr int Nombre d'exemplaires
	 * @return object
	 */
	public static function get_for_shop($level, $nbr)
	{
		$v = ORM::factory('nut')
	    	->where('user_id', '=', 0)
	    	->where('level', '=', $level);
		
		$x = clone $v;
	    $count = $x->count_all();

	    while ($count < $nbr)
		{
			ORM::factory('nut')->generate(0, $level, 0);
			$count++;
		}
		
		return $v->find_all();
	}
	
    /**
	 * Génère une noix aléatoirement
	 * 
	 * @param int $user_id
	 * @param int $level
	 * @param int $rarity
	 */
	public function generate($user_id, $level, $rarity_max=3)
	{
    	// Détermination du nom (8 possibles)
		$name = ceil($level /12.85);
		
		// Rareté
		$rarity = rand(0, $rarity_max);

		// Création de la noix
		$this->user_id 		= $user_id;
		$this->name 		= $name;
		$this->rarity 		= $rarity;
		$this->gender 		= rand(1, 2);   
		$this->level 		= $level;
		$this->price 		= $level * ($rarity +1) +200;
		$this->save();

		// Détermination des effets
		$nbr_effects = $rarity +1; 
		$coeff_max = $nbr_effects * $level;
		$coeffs = num::split_sum($coeff_max, $nbr_effects);
		$t_effects = $this->get_effects();
		$effects_tmp = array_rand($t_effects, $nbr_effects);
		$effects = is_string($effects_tmp) ? array($effects_tmp) : $effects_tmp;

		for($i = 0; $i < $nbr_effects; $i++)
		{
			$value = $t_effects[$effects[$i]][0] + ceil(($t_effects[$effects[$i]][1] - $t_effects[$effects[$i]][0]) *$coeffs[$i] /100 );
			ORM::factory('nut_effect')->create_effect($this->id, $effects[$i], $value);
		}
	}

	/**
	 * Retourne le tableau des caractéristiques d'effets de la noix
	 *
	 * @return Tableau associatif
	 */
	public function get_effects()
	{
		return array(
			'speed' => array(1, 10), 
			'intel' => array(1, 10), 
			'endur' => array(1, 10),
			'lvl_limit' => array(1, 4),
			'yellow' => array(1, 50),
			'red' => array(1, 50),
			'blue' => array(1, 50),
			'green' => array(1, 50),
			'black' => array(1, 50),
			'silver' => array(1, 50),
			'white' => array(1, 50),
			'gold' => array(1, 50),
			'chocobo' => array(1, 50),
			'knight' => array(1, 50),
			'scholar' => array(1, 50),
			'thief' => array(1, 50),
			'ninja' => array(1, 50),
			'whitemage' => array(1, 50),
			'blackmage' => array(1, 50),
			'darkknight' => array(1, 50),
			'dragoon' => array(1, 50),
		);
	}

	/**
	 * Retourne la valeur de l'effet associé à une noix, 0 si non trouvé
	 *
	 * @param string $effect Nom de l'effet
	 * @return int
	 */
	public function get_effect($effect_name)
	{
		foreach ($this->nut_effects->find_all() as $effect)
		{
			if ($effect->name == $effect_name)
			{
				return $effect->value;
				break;
			}
		}

		return 0;
	}
	
    /**
     * Retourne le nom de l'objet (+pop-up)
     *
     * @return Code HTML
     */
	public function vignette() 
	{
		$content = '';
		
		foreach ($this->nut_effects->find_all() as $effect)
		{
			$content .= Kohana::message('nuts', $effect->name) . ' +' . $effect->value . '<br />';
		}
		
		return Vignette::display($this->name(), $content, $this->color());
	}
	
	/**
	 * Retourne la couleur en héxadécimal de la rareté de la noix
	 *
	 * @return String
	 */
	public function color()
	{
		$colors = array('black', 'blue', 'purple', 'orange');
		return $colors[$this->rarity];
	}

	/**
	 * Retourne le nom de la noix
	 * 
	 * @return string
	 */
	public function name()
	{
		switch ($this->name)
		{
			case 1 : $name = "Noix de Peipo"; 	break;
			case 2 : $name = "Noix de Luchile";	break;
			case 3 : $name = "Noix de Sahara";	break;
			case 4 : $name = "Noix de Lasan";	break;
			case 5 : $name = "Noix de Pram";	break;
			case 6 : $name = "Noix de Porov";	break;
			case 7 : $name = "Noix de Caroube";	break;
			case 8 : $name = "Noix de Zeio";	break;
		}
		return $name;
	}
	
	/**
	 * Retourne la liste des couleurs
	 *
	 * @return array
	 */
	public function colors()
	{
		return array(
			'yellow' 	=> 0,
			'red' 		=> 0,
			'blue' 		=> 0,
			'green' 	=> 0,
			'black' 	=> 0,
			'silver' 	=> 0,
			'white' 	=> 0,
			'gold' 		=> 0,
		);
	}

	/**
	 * Initialise le tableau des couleurs selon les effets de la noix
	 * 
	 * @return array
	 */
	public function initiate_colors()
	{
		$res = $this->colors();
		foreach ($this->nut_effects->find_all() as $effect)
		{
			if (array_key_exists($effect->name, $res))
			{
				$res[$effect->name] += $effect->value;
			}
		}
		$this->colors = $res;
	}
	
	/**
	 * Modifie le tableau des couleurs selon la couleur du chocobo
	 * 
	 * @param object $chocobo
	 */
	public function change_colors($chocobo)
	{
		$this->colors[$chocobo->display_colour('code')] += floor($chocobo->level /2);
	}
	
	/**
	 * Retourne la couleur du chocobo selon les caractéristiques de la noix
	 * 
	 * @return int
	 */
	public function choose_color()
	{
		$color = num::rand_pick($this->colors);
		$colors = array_keys($this->colors());
		return $colors[$color];
	}

	/**
	 * Retourne la liste des jobs
	 *
	 * @return array
	 */
	public function jobs()
	{
		return array(
			'chocobo' 		=> 0,
			'knight' 		=> 0,
			'scholar' 		=> 0,
			'thief' 		=> 0,
			'ninja' 		=> 0,
			'whitemage' 	=> 0,
			'blackmage' 	=> 0,
			'darkknight' 	=> 0,
			'dragoon' 		=> 0
		);
	}
	
	/**
	 * Initialise le tableau des jobs selon les effets de la noix
	 * 
	 * @return array
	 */
	public function initiate_jobs()
	{
		$res = $this->jobs();
		foreach ($this->nut_effects->find_all() as $effect)
		{
			if (array_key_exists($effect->name, $res))
			{
				$res[$effect->name] += $effect->value;
			}
		}
		$this->jobs = $res;
	}
	
	/**
	 * Modifie le tableau des jobs selon la couleur du chocobo
	 * 
	 * @param object $chocobo
	 */
	public function change_jobs($chocobo)
	{
		$this->jobs[$chocobo->display_job('code')] += floor($chocobo->level /2);
	}
	
	/**
	 * Retourne la couleur du chocobo selon les caractéristiques de la noix
	 * 
	 * @return int
	 */
	public function choose_job()
	{
		$job = Num::rand_pick($this->jobs);
		$jobs = array_keys($this->jobs());
		return $jobs[$job];
	}

	/**
	 * Achète une noix pour un utilisateur
	 *
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function buy($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cette noix n'existe pas.");
		}

		if ($this->user_id != 0)
		{
			$msg = __("Cette noix n'est pas à vendre.");
		}

		if ($user->gils < $this->price)
		{
			$msg = __("Vous n'avez pas assez d'argent.");
		}

		if ($user->nbr_items() >= $user->get_items())
		{
			$msg = __('Votre inventaire est plein.');
		}

		if ( ! isset($msg))
		{
			$this->user_id = $user->id;
			$this->save();
			
			$user->set_gils(-$this->price);
			$user->save();
			
			$msg = __('Noix achetée !');
		}

		return $msg;
	}

	/**
	 * Vend une noix pour le joueur en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function sale($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cette noix n'existe pas.");
		}

		if ($this->user_id != $user->id)
		{
			$msg = __("Cette noix ne vous appartient pas.");
		}

		if ( ! isset($msg)) 
		{
			$price = floor($this->price /2);
			$user->set_gils($price);
			$user->save();
			
			$this->delete();
			$msg = __('Noix vendue !');
		}

		return $msg;
	}

	/**
	 * Supprime une noix
	 */
	public function delete()
	{
		foreach($this->nut_effects->find_all() as $effect) $effect->delete();

		parent::delete();
	}
	
}
