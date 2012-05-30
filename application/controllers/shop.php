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
	    	->bind('vegetables', $vegetables)
	    	->bind('nuts', $nuts)
	    	->bind('chocobos', $chocobos);

	    $this->authorize('logged_in');
	
		$user = $this->session->get('user');

		// Légumes
		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
	    $level = $levels[$user->shop];
	    
	    $nbr = ORM::factory('vegetable')->where(array('user_id' => 0, 'level' => $level))->count_all();
		while ($nbr < 2)
		{
			ORM::factory('vegetable')->generate(0, $level, 0);
			$nbr++;
		}
		$vegetables = ORM::factory('vegetable')->where(array('user_id' => 0, 'level' => $level))->orderby('level', 'asc')->find_all();

		// Noix
		$levels = array(1, 13, 26, 39, 52, 65, 78, 90);
	    $level = $levels[$user->shop];
	    
	    $nbr = ORM::factory('nut')->where(array('user_id' => 0, 'level' => $level))->count_all();
		while ($nbr < 2)
		{
			ORM::factory('nut')->generate(0, $level, 0);
			$nbr++;
		}
		$nuts = ORM::factory('nut')->where(array('user_id' => 0, 'level' => $level))->orderby('level', 'asc')->find_all();

		// Chocobos
		$level = 16 + $user->shop * 10;

		$nut = ORM::factory('nut');
		for($i = 1; $i < 3; $i++)
		{
			$nbr = ORM::factory('chocobo')->where(array('user_id' => 0, 'gender' => $i, 'lvl_limit' => $level))->count_all();
			if ($nbr == 0)
			{
				$nut->gender = $i;
				ORM::factory('chocobo')->generate(0, $nut, $level);
			}
		}
		$chocobos = ORM::factory('chocobo')->where(array('user_id' => 0, 'lvl_limit' => $level))->orderby('gender', 'asc')->find_all();

	}
	
}
