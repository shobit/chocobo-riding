<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Circuit extends ORM {

	protected $_has_many = array('races' => array());

	/**
	 * Récupère le nom traduit du circuit
	 * 
	 * @return string
	 */
	public function name()
	{
		return Kohana::message('circuits', $this->code.'.name');
	}
    
    public function image($type) 
    {
		$image = ($this->image == "") ? "default.gif" : $this->image;
		return html::image('upload/locations/'.$type.'/'.$image, array('class'=>'location'));
    }
    
    public function delete()
    {
    	$this->db->update(
	   		'races', 
	   		array('circuit_id' => null),
	  		array('circuit_id' => $this->id)
	  	);
	  	parent::delete();
    }
}