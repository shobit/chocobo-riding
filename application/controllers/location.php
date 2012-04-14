<?php defined('SYSPATH') OR die('No direct access allowed.');

class Location_Controller extends Template_Controller {

	// FUNC: liste de toutes les courses de classe du chocobo utilisé
	public function index() 
	{
		$user = $this->session->get('user');
		$this->authorize('logged_in');
		
		$locations = ORM::factory('location')
			->find_all();
		
		$view = new View('locations/index');
		$view->locations = $locations;
		$this->template->content = $view;
	}
	
	public function edit($id=0) 
	{
		// User en session
		$user = $this->session->get('user');
		
		// Construction de location
		$location = ORM::factory('location', $id);
		
		// Droit d'accès
		$this->authorize('logged_in');
		if ($user->id==0 or !$user->has_role(array('admin', 'modo'))) 
			url::redirect('location');
		
		// Initialisation des champs du formulaire
		$form = array(
			'code' 		=> '',
			'image' 	=> '',
			'speed'		=> '',
			'intel' 	=> '',
			'endur' 	=> '',
			'classe' 	=> ''
		);
		
		// Initialisation des champs erreurs
		$errors = $form;
		
		// Remplissage du formulaire avec les données de location
		$form = arr::overwrite($form, $location->as_array());
		
		// Réception du formulaire
		if ($_POST) 
		{
			// Paramètres de la validation
    	    $valid = new Validation($_POST);
    	    $valid->pre_filter('trim', TRUE);
            $valid->add_rules('code', 'required');
            $valid->add_rules('speed', 'required');
            $valid->add_rules('intel', 'required');
            $valid->add_rules('endur', 'required');
            $valid->add_rules('classe', 'required');
            
            $files = Validation::factory($_FILES)
            	->add_rules(
            		'image', 
            		'upload::valid',  
            		'upload::type[gif,jpg,png]', 
            		'upload::size[1M]');
        	
        	// Traitement si valide
        	if ($valid->validate() and $files->validate()) 
        	{
            	// Construction d'un topic 
                $location->code  	= $valid->code;
                $location->speed 	= $valid->speed;
                $location->intel 	= $valid->intel;
                $location->endur 	= $valid->endur;
                $location->classe 	= $valid->classe;
                $location->save();
				
				// traitement et sauvegarde de la nouvelle image
				$filename = upload::save('image');
				if (file_exists($filename)) {
					if ($location->image != "") {
						unlink('upload/locations/mini/'.$location->image);
						unlink('upload/locations/thumbmail/'.$location->image);
					}
					$name = $location->id.'.'.substr(strrchr($filename, '.'), 1);
					Image::factory($filename)
						->resize(50, 50, Image::WIDTH)
						->save(DOCROOT.'upload/locations/mini/'.$name);
					Image::factory($filename)
						->resize(150, 150, Image::WIDTH)
						->save(DOCROOT.'upload/locations/thumbmail/'.$name);
					unlink($filename);
					$location->image = $name;
				}
				
				$location->save();
				
                // Redirection
                url::redirect('location');
            } 
            // Traitement si NON valide
            else 
            {
                // Valeurs du formulaire remises
                $form = arr::overwrite($form, $valid->as_array());
                
                // Erreurs chargées
                $errors = arr::overwrite($errors, $valid->errors('form_error_messages'));
            }
		}
		
		// Construction de la vue
		$view 						= new View('locations/edit');
		$view->location 			= $location;
		$view->form 				= $form;
		$view->errors 				= $errors;
		$this->template->content 	= $view;
	}

}
