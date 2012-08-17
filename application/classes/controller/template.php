<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Template extends Kohana_Controller_Template {

	public $template = "templates/default";
	
	/**
	 * Initialisation
	 */
	public function before()
	{
		parent::before();
		
		// Joueur en session
		$user = Auth::instance()->get_user();

		if ($user !== FALSE)
		{
			// Gestion de tous les chocobos du joueur
			foreach ($user->chocobos->find_all() as $chocobo)
			{
				// Redirection si le chocobo n'a pas de nom
				if (empty($chocobo->name) AND $this->request->uri() != 'chocobos/'.$chocobo->id.'/edit')
				{
					$this->request->redirect('chocobos/'.$chocobo->id.'/edit');
					break;	
				}

				// Met à jour la course actuelle du chocobo
				if ( ! empty($chocobo->race_id))
				{
					$chocobo->race->simulate();
				}

				// Affiche les notifications liés au chocobo
				Model_Result::notify($chocobo);
			}

			// Vérification du chocobo en session
			$chocobo = Session::instance()->get('chocobo');

			if ($chocobo->loaded() === FALSE)
			{
				$chocobo = $user->chocobos->find();
				
				Session::instance()->set('chocobo', $chocobo);
			}
		}
	}

	/**
     * Redirige si l'utilisateur n'a pas accès
     * 
     * @param $status string Classe qui peut accéder à la ressource
     */
    public function authorize($status) 
    {
    	$user = Auth::instance()->get_user();
    	
    	if ($status == 'logged_in' AND ! $user) 
   		{
    		$this->request->redirect('home');
    	}
    	
    	if ($status == 'logged_out' AND $user) 
   		{
    		$this->request->redirect('about#/changelog');
    	}
    	
    	if ($status == 'admin' AND $user AND $user->logged_in('admin') === FALSE)
    	{
    		$this->request->redirect('about#/changelog');
    	}
    }

}