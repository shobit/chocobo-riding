<?php defined('SYSPATH') or die('No direct script access.');
 
class Design_Model extends ORM {
    
    protected $belongs_to = array('user');
    
    protected $has_many = array('users');
    
    public function is_moderator($user)
    {
    	return ($user->has_role(array('admin', 'modo')) or $user->id == $this->user->id);
    }
    
    public function delete()
    {
    	$this->db->update(
	   		'users', 
	   		array('design_id' => null),
	  		array('design_id' => $this->id)
	  	);
	  	parent::delete();
    }
    
}
