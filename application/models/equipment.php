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
		
		// Création de l'équipement
		$this->user_id 		= $user_id;
		$this->name 		= $name;
		$this->rarity 		= $rarity;
		$this->type 		= rand(0, 2);
		$this->resistance 	= $level;
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
					'speed' => array(5, 100), 
					'pl_limit' => array(10, 100), 
					'pl_up' => array(15, 500), 
					'pl_recup' => array(1, 50),
				);
				break;

			case 1: // Harnais
				$res1 = array(
					'endur' => array(5, 100), 
					'hp_limit' => array(10, 100), 
					'hp_up' => array(15, 500), 
					'hp_recup' => array(1, 50),
				);
				break;

			case 2: // Casque
				$res1 = array(
					'intel' => array(5, 100), 
					'mp_limit' => array(10, 100), 
					'mp_up' => array(15, 500), 
					'mp_recup' => array(1, 50),
				);
				break;
			
			default:
				$res1 = array();
				break;

		}

		$res2 = array(
			'bonus_gils' => array(5, 100),
			'bonus_xp' => array(5, 100),
			'windfall' => array(5, 100),
			'resistance' => array(5, 100),
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
		$res  = '';
		$res .= html::anchor(
			'', 
			'<font style="font-weight:bold; color:' . $this->color() . '">' . $this->name() . '</font>', 
			array('class' => 'jtiprel', 'rel' => '#vegetable' . $this->id, 'onclick' => 'return false')
		);
		$res .= '<div id="vegetable' . $this->id . '" style="display:none;">
			<font style="font-weight:bold; color:' . $this->color() . '">' . $this->name() . '</font>
			     <small>';
		
		$res .= "<br />" . Kohana::lang('equipment.resistance') . ' : ' . ($this->resistance + $this->get_effect('resistance'));
		foreach ($this->equipment_effects as $effect)
		{
			$res .= "<br />" . Kohana::lang('equipment.' . $effect->name) . ' +' . $effect->value;
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
			case 1 : $name = "Equipment 1"; break;
			case 2 : $name = "Equipment 2";	break;
			case 3 : $name = "Equipment 3";	break;
			case 4 : $name = "Equipment 4";	break;
			case 5 : $name = "Equipment 5";	break;
			case 6 : $name = "Equipment 6";	break;
			case 7 : $name = "Equipment 7";	break;
			case 8 : $name = "Equipment 8";	break;
		}
		return $name;
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