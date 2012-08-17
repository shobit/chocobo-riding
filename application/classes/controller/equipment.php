<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Equipment extends Controller_Template {

	/**
	 * ACTION: achète un équipement pour le joueur en session
	 *
	 * @param $id int ID de l'équipement
	 */
	public function action_buy()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();

		$equipment = ORM::factory('equipment', $id);

		$msg = $equipment->buy($user);
		
		Jgrowl::add($msg);
		
		$this->request->redirect('shop');
	}

	/**
	 * ACTION: équipe un équipement au chocobo en session
	 * 
	 * @param $id int ID de l'équipement
	 */
	public function action_apply()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$chocobo = Session::instance()->get('chocobo');
		
		$equipment = ORM::factory('equipment', $id);
		
		$msg = $equipment->apply($chocobo);

		Jgrowl::add($msg);
		
		$this->request->redirect('inventory');	
	}
	
	/**
	 * ACTION: déséquipe un équipement au chocobo en session
	 * 
	 * @param $id int ID de l'équipement
	 */
	public function action_desapply()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');
		
		$chocobo = Session::instance()->get('chocobo');
		
		$equipment = ORM::factory('equipment', $id);
		
		$msg = $equipment->desapply($chocobo);

		Jgrowl::add($msg);
		
		$this->request->redirect('inventory');
	}

	/**
	 * ACTION: vend un équipement pour le joueur en session
	 *
	 * @param $id int ID de l'équipement
	 */
	public function action_sale()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$user = Auth::instance()->get_user();
		
		$equipment = ORM::factory('equipment', $id);
		
		$msg = $equipment->sale($user);

		Jgrowl::add($msg);
		
		$this->request->redirect("inventory");
	}
			
}
