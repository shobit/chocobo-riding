<?php
 
class Model_Equipment extends ORM {
	
	protected $_belongs_to = array('user' => array(), 'chocobo' => array());
	protected $_has_many = array('equipment_effects' => array());

	/**
	 * Génère $nbr équipements de $level pour la boutique
	 * 
	 * @param $level int Niveau de l'objet
	 * @param $nbr int Nombre d'exemplaires
	 * @return object
	 */
	public static function get_for_shop($level, $nbr)
	{
		$v = ORM::factory('equipment')
	    	->where('user_id', '=', 0)
	    	->where('level', '=', $level);
		
		$x = clone $v;
		$count = $x->count_all();
		
		while ($count < $nbr)
		{
			ORM::factory('equipment')->generate(0, $level, 0);
			$count++;
		}
		
		return $v->find_all();
	}
	
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
			ORM::factory('equipment_effect')->create_effect($this->id, $effects[$i], $value);
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
		foreach ($this->equipment_effects->find_all() as $effect)
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
		
		foreach ($this->equipment_effects->find_all() as $effect)
		{
			$content .= Kohana::message('equipment', $effect->name) . ' +' . $effect->value . '<br />';
		}
		
		return Vignette::display($this->name(), $content, $this->color());
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
		return Kohana::message('equipment', $this->name);
	}

	/**
	 * Achète un équipement pour un utilisateur
	 *
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function buy($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cet équipement n'existe pas.");
		}

		if ($this->user_id != 0)
		{
			$msg = __("Ce légume n'est pas à vendre.");
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
			$user->update();
			
			$msg = __('Equipement acheté !');
		}

		return $msg;
	}

	/**
	 * Equipe un équipement pour le chocobo en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function apply($chocobo)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cet équipement n'existe pas.");
		}

		if ($this->user_id != $chocobo->user_id)
		{
			$msg = __("Cet équipement ne vous appartient pas.");
		}

		if ($chocobo->race_id != 0)
		{
			$msg = __("Votre chocobo est sur le départ d'une course.");
		}

		if ( ! isset($msg)) 
		{
			// Si le chocobo est déjà équipé sur ce type d'équipement,
			// on le déséquipe.
			foreach ($chocobo->equipment->find_all() as $equip)
			{
				if ($equip->type == $this->type)
				{
					$equip->chocobo_id = 0;
					$equip->update();
				}
			}
			
			$this->chocobo_id = $chocobo->id;
			$this->update();
			$msg = __('Equipement équipé !');
		}

		return $msg;
	}

	/**
	 * Déséquipe un équipement pour le chocobo en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function desapply($chocobo)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cet équipement n'existe pas.");
		}

		if ($this->user_id != $chocobo->user_id)
		{
			$msg = __("Cet équipement ne vous appartient pas.");
		}

		if ($chocobo->race_id != 0)
		{
			$msg = __("Votre chocobo est sur le départ d'une course.");
		}

		if ( ! isset($msg)) 
		{
			$this->chocobo_id = 0;
			$this->save();
			$msg = __('Equipement déséquipé !');
		}

		return $msg;
	}

	/**
	 * Vend un équipement pour le joueur en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function sale($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Cet équipement n'existe pas.");
		}

		if ($this->user_id != $user->id)
		{
			$msg = __("Cet équipement ne vous appartient pas.");
		}

		if ( ! isset($msg)) 
		{
			$price = floor($this->price /2);
			$user->set_gils($price);
			$user->update();
			
			$this->delete();
			$msg = __('Equipement vendu !');
		}

		return $msg;
	}
	
	/**
	 * Supprime un légume
	 */
	public function delete()
	{
		foreach ($this->equipment_effects->find_all() as $effect) $effect->delete();
		
		parent::delete();
	}
	
}