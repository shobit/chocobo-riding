<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Rankings Controller - Affichage des classements
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Rankings_Controller extends Template_Controller 
{

	/**
	 * Affiche l'index des classements
	 *
	 * @param   void
	 * @return  void
	 */
	public function index() 
	{
		$view 						= new View('rankings/index');
		
		$panel 						= new View('rankings/panel');
		$panel->code 				= "";
		$panel->types				= $this->_types();
		$panel->orders				= $this->_orders();
		$panel->labels				= $this->_labels();
		$view->panel				= $panel;
		
		$this->template->content 	= $view;
	}
	
	public function chocobos($orderby="birthday", $page=null, $num=1) 
	{
		$this->_type('chocobos', $orderby, $num);
	}
	
	public function users($orderby="connected", $page=null, $num=1) 
	{		
		$this->_type('users', $orderby, $num);
	}
	
	public function clans($orderby="", $page=null, $num=1) 
	{		
		$this->_type('clans', $orderby, $num);
	}
	
	public function _type($type, $orderby, $num) 
	{
		$types		= $this->_types();
		$orders 	= $this->_orders();
		$labels 	= $this->_labels();
		if (!in_array($orderby, $orders[$type])) $orderby = $orders[$types][3];
		
		switch ($type)
		{
			case 'chocobos':
				${'nbr_'.$types} = ORM::factory('chocobo')->count_all();
				
				// Récupération du nombre de users par page
				$items_per_page = 20;
			
				${$type} = ORM::factory('chocobo')
					->orderby($orderby, 'desc')
					->find_all($items_per_page, ($num-1)*$items_per_page);
				break;
			
			case 'users':
				${'nbr_'.$types} = ORM::factory('user')
					->where('activated >', 0)
					->count_all();
				
				// Récupération du nombre de users par page
				$items_per_page = 20;
			
				${$type} = ORM::factory('user')
					->where('activated >', 0)
					->orderby($orderby, 'desc')
					->find_all($items_per_page, ($num-1)*$items_per_page);
				break;
		}
		
		
		// Paramètres de la pagination
		$pagination = new Pagination(array(
		    'uri_segment' 		=> 'page', 
		    'total_items' 		=> ${'nbr_'.$types}, 
		    'items_per_page' 	=> $items_per_page, 
		    'style' 			=> 'punbb'
		));
		
		$view 						= new View('rankings/'.$type);
		$view->$type 				= ${$type};
		$view->debut 				= ($num-1)*$items_per_page;
		$view->pagination 			= $pagination;
		$view->orderby 				= $orderby;
		
		$panel 						= new View('rankings/panel');
		$panel->code 				= $type.'.'.$orderby;
		$panel->types				= $types;
		$panel->orders				= $orders;
		$panel->labels				= $labels;
		$view->panel				= $panel;
		
		$this->template->content 	= $view;
	}
	
	public function _types() 
	{
		return array("chocobos","users","clans");
	}
	
	public function _orders() 
	{
		return array(
			'chocobos' 	=> array("level","fame","max_speed","birthday"),
			'users' 	=> array("fame","gils","created","connected"),
			'clans' 	=> array()
		);
	}
	
	public function _labels() 
	{	
		return array(
			'types' 	=> array("Chocobos","Jockeys","Clans"),
			'chocobos' 	=> array("Niveau","Côte","Vitesse","Naissance"),
			'users' 	=> array("Côte","Gils","Inscription","Connexion"),
			'clans' 	=> array()
		);	
	}
		
}
