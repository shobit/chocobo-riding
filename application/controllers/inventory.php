<?php
class Inventory_Controller extends Template_Controller {

	public function index()
	{
		
		$this->template->content = View::factory('pages/inventory')
			->bind('user', $user)
			->bind('chocobo', $chocobo)
			->bind('vegetables', $vegetables)
			->bind('nuts', $nuts)
			->bind('equipment', $equipment)
			->bind('nbr_equipped', $nbr_equipped)
			->bind('nbr_items', $nbr_items);

		$this->authorize('logged_in');
		
		$user = $this->session->get('user');
		$chocobo = $this->session->get('chocobo');
		$vegetables = $user->vegetables;
		$nuts = $user->nuts;
		$equipment = $user->equipment;
		$nbr_equipped = count($chocobo->equipment);
		$nbr_items = $user->nbr_items();

	}
			
}
