<?php 
/**
 * Modèle effet d'une noix
 */
class Nut_effect_Model extends ORM 
{
    
    protected $belongs_to = array('nut');
    
    /**
	 * Ajoute un effet de noix
	 * 
	 * @param int $nut_id ID de la noix auquel l'effet est associé
	 * @param int $name Nom de l'effet
	 * @param int $value Valeur de l'effet
	 */
	public function add($nut_id, $name, $value) 
	{
		$this->nut_id 	= $nut_id;
		$this->name 	= $name;
		$this->value 	= $value;
		$this->save();
	}

}
