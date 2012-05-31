<?php 
/**
 * Modèle noix
 */
class Nut_Model extends ORM 
{
	protected $belongs_to = array('user');
	protected $has_many = array('nut_effects');
	
    var $colors = NULL;
    var $jobs = NULL;
	
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
		$this->price 		= $level * ($rarity +1) +60;
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
			ORM::factory('nut_effect')->add($this->id, $effects[$i], $value);
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
     * Retourne le nom de l'objet (+pop-up)
     *
     * @return Code HTML
     */
	public function vignette() 
	{
		$res  = '';
		//$res .= html::image('images/items/vegetables/vegetable'.$this->name.'.gif');
		$res .= html::anchor(
			'', 
			'<font style="font-weight:bold; color:' . $this->color() . '">' . $this->name() . '</font>', 
			array('class' => 'jtiprel', 'rel' => '#nut' . $this->id, 'onclick' => 'return false')
		);
		$res .= '<div id="nut' . $this->id . '" style="display:none;">
			<font style="font-weight:bold; color:' . $this->color() . '">' . $this->name() . '</font>
			     <small>';
		
		foreach ($this->nut_effects as $effect)
		{
			$res .= "<br />" . Kohana::lang('nut.' . $effect->name) . ' +' . $effect->value;
		}
		
		$res .=	'</small>
		</div>';
		return $res;
	}
	
	/**
	 * Retourne la couleur en héxadécimal de la rareté de la noix
	 *
	 * @return String
	 */
	public function color()
	{
		$colors = array('#000', '#009', '#609', '#f60');
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
		foreach ($this->nut_effects as $effect)
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
		foreach ($this->nut_effects as $effect)
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
	public function choose_job($level)
	{
		$job = num::rand_pick($this->jobs);
		$jobs = array_keys($this->jobs());
		return $jobs[$job];
	}
	
}
