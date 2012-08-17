<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Nut extends Controller_Template {

	/**
	 * ACTION: achète une noix pour le joueur en session
	 *
	 * @param $id int ID de la noix
	 */
	public function action_buy()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();

		$nut = ORM::factory('nut', $id);

		$msg = $nut->buy($user);
		
		Jgrowl::add($msg);
		
		$this->request->redirect('shop');
	}

	/**
	 * ACTION: vend une noix pour le joueur en session
	 *
	 * @param $id int ID de la noix
	 */
	public function action_sale()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();
		
		$nut = ORM::factory('nut', $id);
		
		$msg = $nut->sale($user);
		
		Jgrowl::add($msg);

		$this->request->redirect("inventory");
	}
			
}
