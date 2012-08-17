<?php 

class Model_Vegetable_Effect extends ORM {
    
    protected $_belongs_to = array('vegetable' => array());

     /**
	 * Ajoute un effet de légume
	 * 
	 * @param $vegetable_id int ID de la légume
	 * @param $name int Nom de l'effet
	 * @param $value int Valeur de l'effet
	 */
	public function create_effect($vegetable_id, $name, $value) 
	{
		$this->vegetable_id 	= $vegetable_id;
		$this->name 			= $name;
		$this->value 			= $value;
		$this->create();
	}

}
