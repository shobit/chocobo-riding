<?php
class Inventory_Controller extends Template_Controller {

	public function index()
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$chocobo = $this->session->get('chocobo');
		$vegetables = $user->vegetables;
		$nuts = $user->nuts;
		$equipment = $user->equipment;
		$nbr_equipped = count($chocobo->equipment);
		$nbr_items = $user->nbr_items();
		$view = new View('pages/inventory');
		$view->user = $user;
		$view->chocobo = $chocobo;
		$view->vegetables = $vegetables;
		$view->nuts = $nuts;
		$view->equipment = $equipment;
		$view->nbr_equipped = $nbr_equipped;
		$view->nbr_items = $nbr_items;
		$this->template->content = $view;
	}
			
}
