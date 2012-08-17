<?php

class Controller_Shop extends Controller_Template {

	/**
	 * Vue boutique
	 */
	public function action_index() 
	{
		$this->authorize('logged_in');
	
		$user = Auth::instance()->get_user();

		// Légumes
		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
		$level = $levels[$user->shop];
		
		$vegetables = Model_Vegetable::get_for_shop($level, 2);
		
		// Noix
		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
		$level = $levels[$user->shop];

		$nuts = Model_Nut::get_for_shop($level, 2);

		// Equipement
		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
		$level = $levels[$user->shop];

		$equipments = Model_Equipment::get_for_shop($level, 3);
		
		// Chocobos
		$level = 16 + $user->shop * 10;

		$chocobos = Model_Chocobo::get_for_shop($level);

		$this->template->content = View::factory('pages/shop')
			->set('user', $user)
			->set('vegetables', $vegetables)
			->set('nuts', $nuts)
			->set('equipments', $equipments)
			->set('chocobos', $chocobos);
	}
	
}
