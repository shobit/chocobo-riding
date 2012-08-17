<?php defined('SYSPATH') OR die('No direct access allowed.');

class Circuit_Controller extends Admin_Controller {

	/**
	 * (void) liste les circuits du jeu
	 *
	 */
	public function index ( ) 
	{
		$this->template->content = View::factory('admin/circuits/index')
			->bind('circuits', $circuits);
		
		$circuits = ORM::factory('circuit')->find_all();
	}
	
	/**
	 * (void) édite les données d'un circuit
	 *
	 * (int) $id 
	 */
	public function edit ( $id = 0 ) 
	{
		$user = $this->session->get('user');
		
		$circuit = ORM::factory('circuit', $id);
		
		// Initialisation des champs du formulaire
		$form = array(
			'code' 		=> '',
			'image' 	=> '',
			'classe' 	=> ''
		);
		
		// Initialisation des champs erreurs
		$errors = $form;
		
		// Remplissage du formulaire avec les données de circuit
		$form = arr::overwrite($form, $circuit->as_array());
		
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
                $circuit->code  	= $valid->code;
                $circuit->classe 	= $valid->classe;
                $circuit->save();
				
				// traitement et sauvegarde de la nouvelle image
				$filename = upload::save('image');
				if (file_exists($filename)) {
					if ($circuit->image != "") {
						unlink('upload/circuits/mini/'.$circuit->image);
						unlink('upload/circuits/thumbmail/'.$circuit->image);
					}
					$name = $circuit->id.'.'.substr(strrchr($filename, '.'), 1);
					Image::factory($filename)
						->resize(50, 50, Image::WIDTH)
						->save(DOCROOT.'upload/circuits/mini/'.$name);
					Image::factory($filename)
						->resize(150, 150, Image::WIDTH)
						->save(DOCROOT.'upload/circuits/thumbmail/'.$name);
					unlink($filename);
					$circuit->image = $name;
				}
				
				$circuit->save();
				
                // Redirection
                url::redirect('circuit');
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
		$this->template->content = View::factory('admin/circuits/edit')
			->bind('circuit', $circuit)
			->bind('form', $form)
			->bind('errors', $errors);
	}

}
