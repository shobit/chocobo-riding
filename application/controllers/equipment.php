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
			$msg = 'vegetable_not_found';
		}

		if ($equipment->user_id != 0)
		{
			$msg = 'vegetable_not_purchasable';
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
			$msg = 'Légume acheté!';
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
					$equip->chocobo_id = NULL;
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
			$equipment->chocobo_id = NULL;
			$equipment->save();
		}
		
		url::redirect('inventory');
	}
	
	public function sale($id)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$equipment = ORM::factory('equipment')->find($id);
		
		if ($equipment->id >0 and $user->id == $equipment->user_id) 
		{
			$sale = $equipment->price;
			$user->set_gils($sale);
			$user->save();
			
			$equipment->delete();
			gen::add_jgrowl("Vente - Equipement vendu ! (".$sale." Gils)");
		}
		
		url::redirect("inventory");
	}
			
}
