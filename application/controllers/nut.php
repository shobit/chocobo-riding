<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Nut Controller - Vente de légumes
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Nut_Controller extends Template_Controller 
{

	/**
	 * Achète une noix pour le joueur en session
	 *
	 * @param int $id ID de la noix à acheter
	 */
	public function buy($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');

		$nut = ORM::factory('nut', $id);

		if ( ! $nut->loaded)
		{
			$msg = 'nut_not_found';
		}

		if ($nut->user_id != 0)
		{
			$msg = 'nut_not_purchasable';
		}

		if ($user->gils < $nut->price)
		{
			$msg = 'not_enough_gils';
		}

		if ( ! isset($msg))
		{
			$nut->user_id = $user->id;
			$nut->save();
			$user->set_gils(-$nut->price);
			$user->save();
			$msg = 'Noix acheté!';
		}
		
		gen::add_jgrowl($msg);
		url::redirect('shop');
	}

	/**
	 * Vend une noix pour le joueur en session
	 *
	 * @param int $id ID de la noix à vendre
	 */
	public function sale($id)
	{
		$this->authorize('logged_in');
		
		$user = $this->session->get('user');
		
		$nut = ORM::factory('nut', $id);
		
		if ( ! $nut->loaded)
		{
			$msg = 'nut_not_found';
		}

		if ($nut->user_id != $user->id)
		{
			$msg = 'nut_not_owned';
		}

		if ( ! isset($msg)) 
		{
			$user->set_gils($nut->price);
			$user->save();
			
			$nut->delete();
			gen::add_jgrowl('Noix vendu!');
		}
		
		url::redirect("inventory");
	}
			
}
