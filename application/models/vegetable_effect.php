<?php 
/**
 * Modèle effet d'un légume
 */
class Vegetable_effect_Model extends ORM 
{
    
    protected $belongs_to = array('vegetable');
    
    /**
	 * Ajoute un effet de légume
	 * 
	 * @param int $vegetable_id ID du légume auquel l'effet est associé
	 * @param int $name Nom de l'effet
	 * @param int $value Valeur de l'effet
	 */
	public function add($vegetable_id, $name, $value) 
	{
		$this->vegetable_id = $vegetable_id;
		$this->name 		= $name;
		$this->value 		= $value;
		$this->save();
	}

}
