<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Vegetable Controller - Utilisation & vente de légumes
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Vegetable_Controller extends Template_Controller 
{

	/**
	 * Use a vegetable.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function apply($id)
	{
		$chocobo = $this->session->get('chocobo');
		$vegetable = ORM::factory('vegetable')->find($id);
		
		$use = false;
		switch ($vegetable->name)
		{
			// Légume Mimmet - Souffle
			case 1:
				$breath = $chocobo->breath * ($vegetable->value /100 +1);
				$breath_limit = $chocobo->attr('breath_limit');
				$chocobo->breath = min($breath, $breath_limit);
				$use = true;
			break;
		
			// Légume Krakka - Energie
			case 2:
				$hp = $chocobo->hp * ($vegetable->value /100 +1);
				$hp_limit = $chocobo->attr('hp_limit');
				$chocobo->hp = min($hp, $hp_limit);
				$use = true;
			break;
				
			// Légume Pashana - Esprit
			case 3:
				$mp = $chocobo->mp * ($vegetable->value /100 +1);
				$mp_limit = $chocobo->attr('mp_limit');
				$chocobo->mp = min($mp, $mp_limit);
				$use = true;
			break;
			
			// Légume Pashana - Moral
			case 4:
				$moral = $chocobo->moral * ($vegetable->value /100 +1);
				$moral_limit = $chocobo->attr('moral_limit');
				$chocobo->moral = min($moral, $moral_limit);
				$use = true;
			break;
			
			// Légume Curiel - guérit
			case 5:
				$use = true;
			break;
			
			// Légume Guysal - experience
			case 6:
				$use = true;
				$chocobo->evolve($vegetable->value);
			break;
				
			// Légume Reagan - rage
			case 7:
				$rage = $chocobo->rage * ($vegetable->value/100 +1);
				$rage_limit = $chocobo->attr('rage_limit');
				$chocobo->rage = min($rage, $rage_limit);
				$use = true;
			break;
				
			// Légume Tantal - temps de fusion
			case 8:
				$use = true;
			break;
		}
		
		if ($use) 
		{
			gen::add_jgrowl($vegetable->display_name()." utilisé!");
			$chocobo->save();
			$vegetable->delete();
		}
		
		url::redirect('inventory');
	}
	
	public function sale($id)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$vegetable = ORM::factory('vegetable')->find($id);
		
		if ($vegetable->id >0 and $user->id == $vegetable->user_id) {
			$sale = $vegetable->price;
			$user->gils += $sale;
			$user->listen_success(array( # SUCCES
				"gils_500",
				"gils_1000",
				"gils_5000",
				"gils_10000"
			));
			$user->save();
			
			$vegetable->delete();
			gen::add_jgrowl("Vente - Légume vendu ! (".$sale." Gils)");
		}
		
		url::redirect("inventory");
	}
			
}
