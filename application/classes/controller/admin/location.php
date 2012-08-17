<?php

class Location_Controller extends Admin_Controller 
{

	/*Liste des lieux*/
	public function index()
	{
		$this->template->content = View::factory('admin/locations/index')
			->bind('locations', $locations);
		
		$locations = ORM::factory('location')->find_all();
	}

	/*Ajouter/Editer un lieu*/
	public function edit( $id = 0 )
	{
		$this->template->content = View::factory('admin/locations/edit')
			->bind('location', $location)
			->bind('fields', $fields);
		
		$location = ORM::factory('location', $id);
		
		$fields = $location->get_fields();
		
		if ($_POST)
		{
		
			$post = new Validation($_POST);
			$post->pre_filter('trim', TRUE);
			$post->add_rules('ref', 'required', 'alpha_dash');
			$types = $post->type;
			$values = $post->value;
		    
		    if ($post->validate())
			{
				$location->ref = $post->ref;
				$location->classe = $post->classe;
				$location->save();
				
				$location->delete_fields();
				
				for ($i = 0; $i < count($types); $i++)
				{
					if (!empty($types[$i]) && !empty($values[$i])) {
						$field = ORM::factory('location_field');
						$field->location_id = $location->id;
						$field->type = $types[$i];
						$field->value = $values[$i];
						$field->save();
					}
				}
				
				url::redirect('admin/location/index');
			}
			
		}
		
	}
	
	/*Supprime un lieu*/
	public function delete($id)
	{
		ORM::factory('location', $id)->delete();
		url::redirect('admin/location');
	}

}