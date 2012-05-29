<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Nut Controller - Vente de légumes
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Nut_Controller extends Template_Controller 
{

	public function sale($id)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$nut = ORM::factory('nut')->find($id);
		
		if ($nut->id >0 and $user->id == $nut->user_id) {
			$sale = $nut->price;
			$user->set_gils($user->gils + $sale);
			$user->save();
			
			$nut->delete();
			gen::add_jgrowl("Vente - Noix vendue ! (".$sale." Gils)");
		}
		
		url::redirect("inventory");
	}
			
}
