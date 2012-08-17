<?php

class Controller_Vegetable extends Controller_Template {

	/**
	 * ACTION: achète un légume pour le joueur en session
	 *
	 * @param $id int ID du légume
	 */
	public function action_buy()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();
		
		$vegetable = ORM::factory('vegetable', $id);
		
		$msg = $vegetable->buy($user);
		
		Jgrowl::add($msg);
		
		$this->request->redirect('shop');
	}

	/**
	 * ACTION: utilise un légume pour le chocobo en session
	 * 
	 * @param $id int ID du légume
	 */
	public function action_apply()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();

		$chocobo = Session::instance()->get('chocobo');

		$vegetable = ORM::factory('vegetable', $id);

		$msg = $vegetable->apply($chocobo);

		Jgrowl::add($msg);
		
		$this->request->redirect('inventory');
	}

	/**
	 * ACTION: vend un légume pour le joueur en session
	 *
	 * @param $id int ID du légume
	 */
	public function action_sale()
	{
		$this->authorize('logged_in');
		
		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();
		
		$vegetable = ORM::factory('vegetable', $id);

		$msg = $vegetable->sale($user);
		
		Jgrowl::add($msg);
		
		$this->request->redirect("inventory");
	}
			
}
