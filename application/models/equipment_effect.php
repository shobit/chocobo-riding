<?php 
/**
 * Modèle effet d'un légume
 */
class Equipment_effect_Model extends ORM 
{
    
    protected $belongs_to = array('equipment');
    
    /**
	 * Ajoute un effet d'équipement
	 */
	public function add($equipment_id, $name, $value) 
	{
		$this->equipment_id = $equipment_id;
		$this->name 		= $name;
		$this->value 		= $value;
		$this->save();
	}

}
