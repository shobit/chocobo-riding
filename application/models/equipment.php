<?php
 
class Equipment_Model extends ORM {
	
	protected $belongs_to = array('user', 'chocobo');
	protected $has_many = array('equipment_effects');
	
	/**
	 * Génère un équipement aléatoirement
	 * 
	 * @param int $user_id ID du joueur auquel sera associé l'équipment
	 * @param int $level Niveau de l'équipment
	 * @param int $rarity_max Rareté maximum de l'équipement généré
	 */
	public function generate($user_id, $level, $rarity_max=3)
	{
		// Détermination du nom (1ère partie - 8 possibilités)
		$name = ceil($level /12.85);

		// Rareté
		$rarity = rand(0, $rarity_max);
		
		// Type
		$type = rand(0, 2);

		// Création de l'équipement
		$this->user_id 		= $user_id;
		$this->name 		= $type . '_' . $name . '_' . $rarity;
		$this->rarity 		= $rarity;
		$this->type 		= $type;
		$this->resistance 	= $level;
		$this->level 		= $level;
		$this->price 		= $level * ($rarity +1) +120;
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
			ORM::factory('equipment_effect')->add($this->id, $effects[$i], $value);
		}
	}

	/**
	 * Retourne le tableau des caractéristiques d'effets d'un équipement
	 *
	 * @return Tableau associatif
	 */
	public function get_effects()
	{
		switch ($this->type) 
		{

			case 0: // Jambières
				$res1 = array(
					'speed' => array(1, 20), 
					'pl_limit' => array(10, 100), 
					'pl_up' => array(5, 100), 
					'pl_recup' => array(5, 100),
				);
				break;

			case 1: // Harnais
				$res1 = array(
					'endur' => array(1, 20), 
					'hp_limit' => array(10, 500), 
					'hp_up' => array(5, 100), 
					'hp_recup' => array(5, 100),
				);
				break;

			case 2: // Casque
				$res1 = array(
					'intel' => array(1, 20), 
					'mp_limit' => array(5, 50), 
					'mp_up' => array(5, 100), 
					'mp_recup' => array(5, 100),
				);
				break;
			
			default:
				$res1 = array();
				break;

		}

		$res2 = array(
			'rage_limit' => array(1, 10),
			'pc_limit' => array(1, 5),
			'bonus_gils' => array(5, 100),
			'bonus_xp' => array(5, 100),
			'bonus_items' => array(5, 100),
			'resistance' => array(1, 20),
		);
		
		return array_merge($res1, $res2);
	}
	
	/**
	 * Retourne la valeur de l'effet associé à une noix, 0 si non trouvé
	 *
	 * @param string $effect Nom de l'effet
	 * @return int
	 */
	public function get_effect($effect_name)
	{
		foreach ($this->equipment_effects as $effect)
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
     * Affiche le nom de l'objet et au survol un pop-up d'information
     *
     * @return Code HTML
     */
	public function vignette() 
	{
		$content = '';
		
		foreach ($this->equipment_effects as $effect)
		{
			$content .= Kohana::lang('equipment.' . $effect->name) . ' +' . $effect->value . '<br />';
		}
		
		return vignette::display($this->name(), $content, $this->color());
	}

	/**
	 * Retourne la couleur en héxadécimal du nom de l'objet (selon sa rareté)
	 *
	 * @return String
	 */
	public function color()
	{
		$colors = array('black', 'blue', 'purple', 'orange');
		return $colors[$this->rarity];
	}
	
	/**
	 * Retourne le nom de l'objet
	 *
	 * @return String
	 */
	public function name()
	{
		return Kohana::lang('equipment.' . $this->name);
	}
	
	/**
	 * Supprime un légume
	 */
	public function delete()
	{
		foreach ($this->equipment_effects as $effect) $effect->delete();
		
		parent::delete();
	}
	
}