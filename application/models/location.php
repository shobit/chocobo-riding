<?php defined('SYSPATH') or die('No direct script access.');
 
class Location_Model extends ORM {

	protected $has_many = array('circuits');

	public function display_name() 
	{
		return Kohana::lang('location.'.$this->code.'.name');
	}
    
    public function display_image($type) 
    {
		$image = ($this->image == "") ? "default.gif" : $this->image;
		return html::image('upload/locations/'.$type.'/'.$image, array('class'=>'location'));
    }
    
    public function delete()
    {
    	$this->db->update(
	   		'circuits', 
	   		array('location_id' => null),
	  		array('location_id' => $this->id)
	  	);
	  	parent::delete();
    }
}