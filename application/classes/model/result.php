<?php 
 
class Model_Result extends ORM {

	protected $_has_one = array('chocobo' => array());
	protected $_belongs_to = array('race' => array());
	
	/**
	 * Marque comme vu un résultat de course
	 * 
	 * @param $race_id int ID de la course
	 * @param $chocobo_id int ID du chocobo
	 * @return void 
	 */
	public static function mark_as_seen($race_id, $chocobo_id)
	{
		$result = ORM::factory('result')
			->where('chocobo_id', '=', $chocobo_id)
			->where('race_id', '=', $race_id)
			->find();

		if ( $result->loaded() AND $result->seen == FALSE)
		{
			$result->seen = TRUE;
			$result->update();
		}
	}

	/**
	 * Notifie un chocobo du résultat de course
	 * 
	 * @param $chocobo objet Model_Chocobo
	 * @return void 
	 */
	public static function notify($chocobo)
	{
		// Repère si le chocobo possède un historique de course non vu et non notifié
		$result = ORM::factory('result')
			->where('chocobo_id', '=', $chocobo->id)
			->where('notified', '=', FALSE)
			->find();
		
		if ($result->loaded() AND $result->notified == FALSE) 
		{	
			$result->notified = TRUE;
			$result->update();
			
			Jgrowl::add(html::anchor('races/'.$result->race_id, __('Course terminée !'), array('class' => 'jgrowl')));
		}
	}
	
	/**
	 * Marque comme supprimé le résultat de course
	 * et supprime la course liée si tous les autres résultats ont été supprimés
	 *
	 * @return void 
	 */
	public function to_delete()
	{
		$nbr = ORM::factory('result')
			->where('race_id', '=', $this->race_id)
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