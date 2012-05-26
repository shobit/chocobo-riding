<?php
/**
 * Contrôleur boutique
 */
class Shop_Controller extends Template_Controller 
{

	/**
	 * Affiche la boutique
	 */
	public function index() 
	{
		$this->template->content = View::factory('pages/shop')
	    	->bind('user', $user)
	    	->bind('vegetables', $vegetables);

	    $this->authorize('logged_in');
	
		$user = $this->session->get('user');

	    $vegetables = ORM::factory('vegetable')->where('user_id', 0)->find_all();
    }
	
}
