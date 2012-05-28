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
		$this->authorize('logged_in');

		$user = $this->session->get('user');

		$chocobo = $this->session->get('chocobo');

		$vegetable = ORM::factory('vegetable', $id);

		if ( ! $vegetable->loaded)
		{
			$msg = 'vegetable_not_found';
		}

		if ($vegetable->user_id != $user->id)
		{
			$msg = 'vegetable_not_owned';
		}

		if ( ! isset($msg))
		{
			foreach($vegetable->vegetable_effects as $effect)
			{
				switch($effect->name)
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
		}
		
		url::redirect('inventory');
	}
	
	/**
	 * Achète un légume pour le joueur en session
	 *
	 * @param int $id ID du légume à acheter
	 */
	public function buy($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');

		$vegetable = ORM::factory('vegetable', $id);

		if ( ! $vegetable->loaded)
		{
			$msg = 'vegetable_not_found';
		}

		if ($vegetable->user_id == 0)
		{
			$msg = 'vegetable_not_purchasable';
		}

		if ($user->gils >= $vegetable->price)
		{
			$msg = 'not_enough_gils';
		}

		if ( ! isset($msg))
		{
			$vegetable->user_id = $user->id;
			$user->set_gils($vegetable->price);
			$user->save();
			gen::add_jgrowl('Légume acheté!');
		}
		
		url::redirect('shop');
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
		
		if ( ! $vegetable->loaded)
		{
			$msg = 'vegetable_not_found';
		}

		if ($vegetable->user_id != $user->id)
		{
			$msg = 'vegetable_not_owned';
		}

		if ( ! isset($msg)) 
		{
			$sale = $vegetable->price;
			$user->set_gils($sale);
			$user->save();
			
			$vegetable->delete();
			gen::add_jgrowl('Légume vendu!');
		}
		
		url::redirect("inventory");
	}
			
}
