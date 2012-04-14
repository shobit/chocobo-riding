<?php defined('SYSPATH') or die('No direct script access.');
 
class Comment_Model extends ORM {
    protected $belongs_to = array('topic', 'user');
    protected $has_many = array('interests');
      
    // TODO
    public function is_first() 
    {
    	$first_comment = $this->topic->comments[0];
    	return ($this->id == $first_comment->id);
    }
    
    public function delete()
    {
    	foreach ($this->interests as $interest) $interest->delete();
		parent::delete();
    }
 
}
