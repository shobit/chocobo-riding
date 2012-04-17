<?php defined('SYSPATH') or die('No direct script access.');
 
class Circuit_Model extends ORM {

	protected $has_many = array('races');

	public function name() 
	{
		return Kohana::lang('circuit.' . $this->code . '.name');
	}
    
    public function display_image($type) 
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