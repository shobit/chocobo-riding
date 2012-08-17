<?php 

class Model_Nut_Effect extends ORM {
    
    protected $_belongs_to = array('nut' => array());

    /**
	 * Ajoute un effet de noix
	 * 
	 * @param $nut_id int ID de la noix
	 * @param $name int Nom de l'effet
	 * @param $value int Valeur de l'effet
	 */
	public function create_effect($nut_id, $name, $value) 
	{
		$this->nut_id 	= $nut_id;
		$this->name 	= $name;
		$this->value 	= $value;
		$this->save();
	}

}
