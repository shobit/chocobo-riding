<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Title extends ORM {

	protected $_has_many = array('successes' => array());

	/**
	 * Supprime le succès
	 */
	public function delete()
	{
		foreach ($this->successes->find_all() as $success) $success->delete(); 
		
		parent::delete();
	}	

}