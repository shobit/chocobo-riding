<?php defined('SYSPATH') or die('No direct script access.');
 
class Title_Model extends ORM {

	protected $has_many = array('successes');

	public function delete()
	{
		foreach ($successes as $success) $success->delete(); 
		parent::delete();
	}	

}