<?php 
 
class Result_Model extends ORM {

	protected $has_one = array('chocobo');
	protected $belongs_to = array('race');
	
	public function display_gain_apt()
	{
		return number_format($this->gain_xp/100, 2, '.', '');
	}
	
	public function display_gain_fame()
	{
		return number_format($this->gain_fame, 2, '.', '');
	}
	
	/**
	 * marque comme supprimé l'historique de course du joueur
	 * et supprime la course liée si tous les autres historiques ont été supprimés
	 *
	 */
	public function to_delete ()
	{
		$nbr = ORM::factory('result')
			->where('race_id', $this->race_id)
			->count_all();
		
		$this->deleted = TRUE;
		$this->save();
		
		$nbr--;
		
		if ($nbr == 0)
		{
			$this->race->delete();
		}
	}
	
}