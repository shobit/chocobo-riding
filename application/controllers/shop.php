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

		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
	    foreach ($levels as $level)
	    {
		    $nbr = ORM::factory('vegetable')->where(array('user_id' => 0, 'level' => $level))->count_all();
		    if ($nbr == 0) ORM::factory('vegetable')->generate(0, $level, 0);
        }
		$vegetables = ORM::factory('vegetable')->where('user_id', 0)->orderby('level', 'asc')->find_all();
    }
	
}
