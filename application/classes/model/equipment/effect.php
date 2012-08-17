<?php 

class Model_Equipment_Effect extends ORM {
    
    protected $_belongs_to = array('equipment' => array());
    
     /**
	 * Ajoute un effet d'équipement
	 * 
	 * @param $equipment_id int ID de l'équipement
	 * @param $name int Nom de l'effet
	 * @param $value int Valeur de l'effet
	 */
	public function create_effect($equipment_id, $name, $value) 
	{
		$this->equipment_id = $equipment_id;
		$this->name 		= $name;
		$this->value 		= $value;
		$this->save();
	}

}
