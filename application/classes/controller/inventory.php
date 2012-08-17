<?php

class Controller_Inventory extends Controller_Template {

	/**
	 * Vue de l'inventaire
	 */
	public function action_index()
	{
		$this->authorize('logged_in');
		
		$user = Auth::instance()->get_user();
		$chocobo = Session::instance()->get('chocobo');
		
		$nbr_equipped = $user->equipment->where('chocobo_id', '>', 0)->count_all();
		$nbr_items = $user->nbr_items();

		$this->template->content = View::factory('pages/inventory')
			->set('user', $user)
			->set('chocobo', $chocobo)
			->set('nbr_equipped', $nbr_equipped)
			->set('nbr_items', $nbr_items);
	}
			
}
