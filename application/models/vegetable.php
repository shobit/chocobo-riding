<?php 
/**
 * Modèle légume
 */
class Vegetable_Model extends ORM 
{
    
    protected $belongs_to = array('user');
    protected $has_many = array('vegetable_effects');
    
    /**
	 * Génère un légume aléatoirement
	 * 
	 * @param int $user_id ID du joueur auquel sera associé le légume
	 * @param int $level Niveau du légume
	 * @param int $rarity_max Rareté maximum du légume généré
	 */
	public function generate($user_id, $level, $rarity_max=3)
	{
		// Détermination du nom (8 possibles)
		$name = ceil($level /12.85);
		
		// Rareté
		$rarity = rand(0, $rarity_max);
		
		// Création du légume
		$this->user_id 		= $user_id;
		$this->name 		= $name;
		$this->rarity 		= $rarity;
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
			ORM::factory('vegetable_effect')->add($this->id, $effects[$i], $value);
		}
	}

	/**
	 * Retourne le tableau des caractéristiques d'effets de légume
	 *
	 * @return Tableau associatif
	 */
	public function get_effects()
	{
		return array(
			'xp' => array(5, 100), 
			'pl' => array(10, 100), 
			'hp' => array(15, 500), 
			'mp' => array(1, 50), 
			'rage' => array(1, 50),
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
			array('class' => 'jtiprel', 'rel' => '#vegetable' . $this->id)
		);
		$res .= '<div id="vegetable' . $this->id . '" style="display:none;">
			<font style="font-weight:bold; color:' . $this->color() . '">' . $this->name() . '</font>
			     <small>';
		
		foreach ($this->vegetable_effects as $effect)
		{
			$res .= "<br />" . Kohana::lang('chocobo.' . $effect->name) . ' +' . $effect->value;
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
	 * Retourne le nom de l'objet
	 *
	 * @return String
	 */
	public function name()
	{
		switch ($this->name)
		{
			case 1 : $name = "Légume Gyshal"; 	break;
			case 2 : $name = "Légume Krakka";	break;
			case 3 : $name = "Légume Tantal";	break;
			case 4 : $name = "Légume Pahsana";	break;
			case 5 : $name = "Légume Curiel";	break;
			case 6 : $name = "Légume Mimett";	break;
			case 7 : $name = "Légume Reagan";	break;
			case 8 : $name = "Légume Sylkis";	break;
		}
		return $name;
	}

	/**
	 * Supprime un légume
	 */
	public function delete()
	{
		foreach($this->vegetable_effects as $effect) $effect->delete();

		parent::delete();
	}
	    
}
