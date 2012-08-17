<?php 

class Model_Vegetable extends ORM {
	
	protected $_belongs_to = array('user' => array());
	protected $_has_many = array('vegetable_effects' => array());
	
	/**
	 * Génère $nbr légumes de $level pour la boutique
	 * 
	 * @param $level int Niveau de l'objet
	 * @param $nbr int Nombre d'exemplaires
	 * @return object
	 */
	public static function get_for_shop($level, $nbr)
	{
		$v = ORM::factory('vegetable')
			->where('user_id', '=', 0)
			->where('level', '=', $level);
		
		$x = clone $v;
		$count = $x->count_all();
		
		while ($count < $nbr)
		{
			ORM::factory('vegetable')->generate(0, $level, 0);
			$count++;
		}
		
		return $v->find_all();
	}

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
		$this->create();

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
			ORM::factory('vegetable_effect')->create_effect($this->id, $effects[$i], $value);
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
		$content = '';
		
		foreach ($this->vegetable_effects->find_all() as $effect)
		{
			$content .= Kohana::message('vegetables', $effect->name) . ' +' . $effect->value . '<br />';
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
	 * @return string
	 */
	public function name()
	{
		switch ($this->name)
		{
			case 1 : $name = __('Légume Gyshal'); 		break;
			case 2 : $name = __('Légume Krakka');		break;
			case 3 : $name = __('Légume Tantal');		break;
			case 4 : $name = __('Légume Pahsana');		break;
			case 5 : $name = __('Légume Curiel');		break;
			case 6 : $name = __('Légume Mimett');		break;
			case 7 : $name = __('Légume Reagan');		break;
			case 8 : $name = __('Légume Sylkis');		break;
		}
		return $name;
	}

	/**
	 * Achète un légume pour un utilisateur
	 *
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function buy($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Ce légume n'existe pas.");
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
			$this->update();

			$user->set_gils(-$this->price);
			$user->update();
		
			$msg = __('Légume acheté !');
		}

		return $msg;
	}

	/**
	 * Vend un légume pour le joueur en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function sale($user)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Ce légume n'existe pas.");
		}

		if ($this->user_id != $user->id)
		{
			$msg = __("Ce légume ne vous appartient pas.");
		}

		if ( ! isset($msg)) 
		{
			$price = floor($this->price /2);
			$user->set_gils($price);
			$user->save();
			
			$this->delete();
			$msg = __('Légume vendu !');
		}

		return $msg;
	}

	/**
	 * Utilise un légume pour le chocobo en session
	 * 
	 * @param $user object 
	 * @return string Message de retour
	 */
	public function apply($chocobo)
	{
		if ($this->loaded() === FALSE)
		{
			$msg = __("Ce légume n'existe pas.");
		}

		if ($this->user_id != $chocobo->user->id)
		{
			$msg = __("Ce légume ne vous appartient pas.");
		}

		if ( ! isset($msg))
		{
			foreach($this->vegetable_effects->find_all() as $effect)
			{
				switch($effect->name)
				{
					case 'xp':
						$chocobo->set_exp($effect->value);
						break;
					case 'pl':
						$chocobo->set_pl($effect->value);
						break;
					case 'hp':
						$chocobo->set_hp($effect->value);
						break;
					case 'mp':
						$chocobo->set_mp($effect->value);
						break;
					case 'rage':
						$chocobo->set_rage($effect->value);
						break;
				}
			}
			
			$chocobo->save();
			$this->delete();	
			$msg = __('Légume utilisé !');
		}

		return $msg;
	}

	/**
	 * Supprime un légume
	 * 
	 * @return void
	 */
	public function delete()
	{
		foreach ($this->vegetable_effects->find_all() as $effect) $effect->delete();

		parent::delete();
	}
		
}
