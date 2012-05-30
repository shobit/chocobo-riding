<?php 
/**
 * Modèle noix
 */
class Nut_Model extends ORM 
{
	protected $belongs_to = array('user');
	protected $has_many = array('nut_effects');
	
    var $colours = array(); # array[8]
	var $jobs = array();
	
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
     * Affiche le nom de l'objet et au survol un pop-up d'information
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
	 * Retourne la couleur en héxadécimal du nom de l'objet (selon sa rareté)
	 *
	 * @return String
	 */
	public function color()
	{
		$colors = array('#000', '#009', '#609', '#f60');
		return $colors[$this->rarity];
	}

	/**
	 * Display nut name
	 * 
	 * @access public
	 * @return void
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
	 * Init colours
	 * 
	 * @access public
	 * @return void
	 */
	public function initiate_colours()
	{
		switch ($this->name)
		{
			case 1 : $res = array(0, 0, 0, 0, 0, 0, 0, 0); $num = 0; break; // jaune
			case 2 : $res = array(0,10, 0, 0, 0, 0, 0, 0); $num = 1; break; // rouge
			case 3 : $res = array(0, 0,10, 0, 0, 0, 0, 0); $num = 2; break; // bleu
			case 4 : $res = array(0, 0, 0,10, 0, 0, 0, 0); $num = 3; break; // vert
			case 5 : $res = array(0,10, 0,10, 5, 0, 0, 0); $num = 4; break; // noir
			case 6 : $res = array(0, 0,10,10, 0, 5, 0, 0); $num = 5; break; // argent
			case 7 : $res = array(0,10, 0,10, 0, 0, 5, 0); $num = 6; break; // blanc
			case 8 : $res = array(0,10,10,10, 5, 5, 5, 1); $num = 7; break; // or
		}
		if ($this->colour > 0) $res[$num] += $this->colour;
		$this->colours = $res;
	}
	
	/**
	 * Change colours with chocobo stats
	 * 
	 * @access public
	 * @param mixed $chocobo
	 * @return void
	 */
	public function change_colours($chocobo)
	{
		$s = $chocobo->speed;
		$i = $chocobo->intel;
		$e = $chocobo->endur;
		if 		($s>100 and $i>100 and $e>100) 	$num = 7; 
		elseif 	($s>75 and $i>50 and $e>75) 	$num = 6;
		elseif 	($s>50 and $i>75 and $e>75) 	$num = 5;
		elseif 	($s>75 and $i>75 and $e>50) 	$num = 4;
		elseif 	($s>25 and $i>25 and $e>50) 	$num = 3;
		elseif 	($s>25 and $i>50 and $e>25) 	$num = 2;
		elseif 	($s>50 and $i>25 and $e>25) 	$num = 1;
		else 									$num = 0;
		$this->colours[$num] += 5; 
	}
	
	/**
	 * Retourne la couleur du chocobo selon les caractéristiques de la noix
	 * 
	 * @return int
	 */
	public function choose_colour()
	{
		return 0;
	}
	
	public function initiate_jobs()
	{
		$this->jobs = array(11, 10, 8, 7, 5, 4, 2, 1);
	}
	
	public function change_jobs($chocobo)
	{
		$this->jobs[$chocobo->job] += 5;
	}
	
	public function choose_job($level)
	{
		$paliers = array(20, 40, 30, 50, 40, 60, 50, 70);
		$res = 0; $i = 7;
		while ($i >= 0 and $res == 0)
		{
			if ($level >= $paliers[$i])
			{
				$rand = rand(1, 100);
				$chance = $this->colours[$i] +($level -$paliers[$i]) /2;
				if ($rand <= $chance) $res = $i;
			}
			$i --;
		}
		return $res;
	}
	
}
