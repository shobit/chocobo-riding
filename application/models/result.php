<?php 
 
class Result_Model extends ORM {

	protected $has_one = array('chocobo');
	protected $belongs_to = array('race');
	protected $has_many = array('facts');
	
	public function display_gain_apt()
	{
		return number_format($this->gain_xp/100, 2, '.', '');
	}
	
	public function display_gain_fame()
	{
		return number_format($this->gain_fame, 2, '.', '');
	}
	
	/**
	 * Add a fact linked to a result ( chocobo & circuit )
	 * 
	 * @access public
	 * @param mixed $action
	 * @param mixed $value
	 * @param bool $public. (default: true)
	 * @return void
	 */
	public function add_fact($action, $value, $public=true)
	{
		$fact = ORM::factory('fact');
        $fact->result_id 	= $this->id;
        $fact->action 		= $action;
        $fact->values 		= $value;
        $fact->general 		= $public;
        $fact->save();
	}
	
	public function delete()
	{
		foreach ($this->facts as $fact) $fact->delete();
		parent::delete();
	}
	
}