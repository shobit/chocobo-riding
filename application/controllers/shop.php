<?php defined('SYSPATH') OR die('No direct access allowed.');

class Shop_Controller extends Template_Controller {

	public $PRICE_VEGETABLE_1 	= 50;
	public $PRICE_VEGETABLE_2 	= 60;
	public $PRICE_NUT 			= 300;
	public $PRICE_EQUIPMENT_1 	= 150;
	public $PRICE_EQUIPMENT_2 	= 150;
	public $PRICE_EQUIPMENT_3 	= 150;
	public $PRICE_CHOCOBO_M 	= 250;
	public $PRICE_CHOCOBO_F 	= 250;
	public $PRICE_LICENCE 		= 100;
	public $PRICE_BIG_BAG 		= 200;
	
	public function index() 
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');		
		$view = new View('pages/shop');
		$view->user = $user;
		$view->PRICE_VEGETABLE_1 	= $this->PRICE_VEGETABLE_1;
		$view->PRICE_VEGETABLE_2 	= $this->PRICE_VEGETABLE_2;
		$view->PRICE_NUT 			= $this->PRICE_NUT;
		$view->PRICE_EQUIPMENT_1 	= $this->PRICE_EQUIPMENT_1;
		$view->PRICE_EQUIPMENT_2 	= $this->PRICE_EQUIPMENT_2;
		$view->PRICE_EQUIPMENT_3 	= $this->PRICE_EQUIPMENT_3;
		$view->PRICE_CHOCOBO_M 		= $this->PRICE_CHOCOBO_M;
		$view->PRICE_CHOCOBO_F 		= $this->PRICE_CHOCOBO_F;
		$view->PRICE_LICENCE 		= $this->PRICE_LICENCE;
		$view->PRICE_BIG_BAG 		= $this->PRICE_BIG_BAG;
        $this->template->content = $view;
	}
	
	public function buy($item)
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$gils = $user->gils;
		$boxes = $user->boxes;
		
		$nbr_equipment = ORM::factory('equipment', array('user_id' => $user->id))->count_all();
		$nbr_nuts = ORM::factory('nut', array('user_id' => $user->id))->count_all();
		$nbr_vegetables = ORM::factory('vegetable', array('user_id' => $user->id))->count_all();
		$nbr_items = $nbr_equipment + $nbr_nuts + $nbr_vegetables;
		
		$max_items = $user->items;
		$nbr_chocobos = count($user->chocobos);
		$restants = $boxes - $nbr_chocobos;
		
		switch($item)
		{
			// Légume Mimmet
			case "vegetable1":
				$price = $this->PRICE_VEGETABLE_1;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$vegetable = ORM::factory('vegetable');
					$vegetable->user_id = $user->id;
					$vegetable->name = 1;
					$vegetable->value = 30;
					$vegetable->price = 5;
					$vegetable->save();
					
					gen::add_jgrowl("Achat - Légume Mimmet acheté! (".$price." Gils)");
				}
				break;
			
			// Légume Krakka
			case "vegetable2":
				$price = $this->PRICE_VEGETABLE_2;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$vegetable = ORM::factory('vegetable');
					$vegetable->user_id = $user->id;
					$vegetable->name = 2;
					$vegetable->value = 25;
					$vegetable->price = 6;
					$vegetable->save();
					
					gen::add_jgrowl("Achat - Légume Krakka acheté! (".$price." Gils)");
				}
				break;
			
			// Noix de Peipo
			case "nut":
				$price = $this->PRICE_NUT;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$nut = ORM::factory('nut');
					$nut->user_id = $user->id;
					$nut->name = 1;
					$nut->gender = rand(1, 2);
					$nut->level = 1;
					$nut->price = 10;
					$nut->save();
					
					gen::add_jgrowl("Achat - Noix achetée! (".$price." Gils)");
				}
				break;
				
			// Jambières de vitesse
			case "equipment1":
				$price = $this->PRICE_EQUIPMENT_1;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$equipment = ORM::factory('equipment');
					$equipment->user_id 	= $user->id;
					$equipment->type 		= 1;
					$equipment->name 		= "1_0_1";
					$equipment->resistance 	= 10;
					$equipment->price 		= 30;
					$equipment->save();
					
					$effect = ORM::factory('effect');
					$effect->equipment_id = $equipment->id;
					$effect->name = "speed";
					$effect->value = 1;
					$effect->save();
					
					gen::add_jgrowl("Achat - Jambières de vitesse achetées! (".$price." Gils)");
				}
				break;
				
			// Harnais d'endurance
			case "equipment2":
				$price = $this->PRICE_EQUIPMENT_2;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$equipment = ORM::factory('equipment');
					$equipment->user_id 	= $user->id;
					$equipment->type 		= 2;
					$equipment->name 		= "2_0_1";
					$equipment->resistance 	= 10;
					$equipment->price 		= 30;
					$equipment->save();
					
					$effect = ORM::factory('effect');
					$effect->equipment_id = $equipment->id;
					$effect->name = "endur";
					$effect->value = 1;
					$effect->save();
					
					gen::add_jgrowl("Achat - Harnais d'endurance acheté! (".$price." Gils)");
				}
				break;
				
			// Casque d'intelligence
			case "equipment3":
				$price = $this->PRICE_EQUIPMENT_3;
				if ($gils >= $price and $nbr_items < $max_items) 
				{
					$gils -= $price;
					
					$equipment = ORM::factory('equipment');
					$equipment->user_id 	= $user->id;
					$equipment->type 		= 3;
					$equipment->name 		= "3_0_1";
					$equipment->resistance 	= 10;
					$equipment->price 		= 30;
					$equipment->save();
					
					$effect = ORM::factory('effect');
					$effect->equipment_id = $equipment->id;
					$effect->name = "intel";
					$effect->value = 1;
					$effect->save();
					
					gen::add_jgrowl("Achat - Casque d'intelligence acheté! (".$price." Gils)");
				}
				break;
				
			// Chocobo mâle
			case "chocobo_m":
				$price = $this->PRICE_CHOCOBO_M + ($nbr_chocobos-1)*100;
				if ($gils >= $price and $restants > 0) 
				{
					$gils -= $price; 
					
					$nut = ORM::factory('nut');
					$nut->gender = 2;
					$chocobo = Chocobo_Model::add_one($user->id, $nut);
					
					gen::add_jgrowl("Achat - Chocobo (".$chocobo->display_gender("zone").") acheté! (".$price." Gils)");
				}
				break;
				
			// Chocobo femelle
			case "chocobo_f":
				$price = $this->PRICE_CHOCOBO_F + ($nbr_chocobos-1)*100;
				if ($gils >= $price and $restants > 0) 
				{
					$gils -= $price; 
					
					$nut = ORM::factory('nut');
					$nut->gender = 1;
					$chocobo = Chocobo_Model::add_one($user->id, $nut);
					
					gen::add_jgrowl("Achat - Chocobo (".$chocobo->display_gender("zone").") acheté! (".$price." Gils)");
				}
				break;
			
			// License
			case "licence":
				$price = $this->PRICE_LICENCE + ($boxes-2)*300;
				if ($gils >= $price and $boxes < $user->BOXES_LIMIT) 
				{
					$gils -= $price;
					
					$user->boxes = $boxes+1;
					$user->listen_success(array( # SUCCES
						"boxes_3",
						"boxes_5",
						"boxes_7"
					));
				
					gen::add_jgrowl("Achat - Licence achetée! (".$price." Gils)");
				}
			break;
			
			// Grand sac
			case "big_bag":
				$price = $this->PRICE_LICENCE + ($max_items - 10) * 200;
				if ($gils >= $price) 
				{
					$gils -= $price;
					
					$user->items = $max_items + 2;
					$user->listen_success("items_12"); #SUCCES
					$user->listen_success("items_15");
					$user->listen_success("items_20");
				
					gen::add_jgrowl("Achat - Licence achetée! (".$price." Gils)");
				}
			break;
		}
		
		$user->gils = $gils;
        $user->save();
		
		url::redirect('shop');
	}
	
}
