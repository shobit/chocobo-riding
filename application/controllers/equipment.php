<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Nut Controller - Vente et équipement d'équipements
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Equipment_Controller extends Template_Controller 
{

	/**
	 * Achète un équipement pour le joueur en session
	 *
	 * @param int $id ID de l'équipement à acheter
	 */
	public function buy($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');

		$equipment = ORM::factory('equipment', $id);

		if ( ! $equipment->loaded)
		{
			$msg = 'equipment_not_found';
		}

		if ($equipment->user_id != 0)
		{
			$msg = 'equipment_not_purchasable';
		}

		if ($user->gils < $equipment->price)
		{
			$msg = 'not_enough_gils';
		}

		if ($user->nbr_items() >= $user->get_items())
		{
			$msg = 'full_inventory';
		}

		if ( ! isset($msg))
		{
			$equipment->user_id = $user->id;
			$equipment->save();
			$user->set_gils(-$equipment->price);
			$user->save();
			$msg = 'Equipement acheté!';
		}
		
		gen::add_jgrowl($msg);
		url::redirect('shop');
	}

	public function apply($id)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$chocobo = $this->session->get('chocobo');
		$equipment = ORM::factory('equipment')->find($id);
		
		if ($equipment->id >0 and 
			$user->id == $equipment->user_id and
			$chocobo->race_id == 0) 
		{
			foreach ($chocobo->equipment as $equip)
			{
				if ($equip->type == $equipment->type)
				{
					$equip->chocobo_id = 0;
					$equip->save();
				}
			}
			
			$equipment->chocobo_id = $chocobo->id;
			$equipment->save();
		}
		
		url::redirect('inventory');	
	}
	
	public function desapply($id)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$chocobo = $this->session->get('chocobo');
		$equipment = ORM::factory('equipment')->find($id);
		
		if ($equipment->id >0 and 
			$user->id == $equipment->user_id and
			$chocobo->race_id == 0) 
		{
			$equipment->chocobo_id = 0;
			$equipment->save();
		}
		
		url::redirect('inventory');
	}

	/**
	 * Vend un équipement pour le joueur en session
	 *
	 * @param int $id ID de l'équipement à vendre
	 */
	public function sale($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');
		
		$equipment = ORM::factory('equipment', $id);
		
		if ( ! $equipment->loaded)
		{
			$msg = 'equipment_not_found';
		}

		if ($equipment->user_id != $user->id)
		{
			$msg = 'equipment_not_owned';
		}

		if ( ! isset($msg)) 
		{
			$price = floor($equipment->price /2);
			$user->set_gils($price);
			$user->save();
			
			$equipment->delete();
			gen::add_jgrowl('Equipement vendu!');
		}
		
		url::redirect("inventory");
	}
			
}
