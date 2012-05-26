<?php
/**
 * Contrôleur légume
 */
class Vegetable_Controller extends Template_Controller 
{

	/**
	 * consomme un légume pour le chocobo en session
	 * 
	 * @param int $id ID du légume à consommer
	 */
	public function apply($id)
	{
		$chocobo = $this->session->get('chocobo');

		$vegetable = ORM::factory('vegetable', $id);

		foreach($vegetable->vegetable_effect as $effect)
		{
			switch ($effect->name)
			{
				case 'xp':
					$chocobo->set_exp($effect->value);
					break;
				case 'pl':
					$chocobo->pl += $effect->value;
					break;
				case 'hp':
					$chocobo->hp += $effect->value;
					break;
				case 'mp':
					$chocobo->mp += $effect->value;
					break;
			}
		}
		
		$chocobo->save();
		$vegetable->delete();
		gen::add_jgrowl("Légume utilisé!");
		
		url::redirect('inventory');
	}
	
	/**
	 * Vend un légume pour le joueur en session
	 *
	 * @param int $id ID du légume à vendre
	 */
	public function sale($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');
		
		$vegetable = ORM::factory('vegetable', $id);
		
		if ($vegetable->loaded and $user->id == $vegetable->user_id) 
		{
			$sale = $vegetable->price;
			$user->set_gils($sale);
			$user->save();
			
			$vegetable->delete();
			gen::add_jgrowl("Vente - Légume vendu ! (" . $sale . " Gils)");
		}
		
		url::redirect("inventory");
	}
			
}
