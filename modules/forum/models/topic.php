<?php defined('SYSPATH') or die('No direct script access.');

class Topic_Model extends ORM {
    
    protected $has_many = array('comments');
	
    // vérifie sur le joueur a le droit de lecture/écriture sur un sujet
    public function allow ( $user, $action = 'r' ) 
    {
    	if ($action == 'r')
    	{
	    	return true;
	    }
 		
 		if ($action == 'w')
 		{
 			if ($user->loaded and ! $this->loaded) // le sujet est nouveau
 			{
 				return true;
 			}
 			else if ($user->loaded and $this->loaded) // ou il existe
 			{
 				$comment = $this->comments[0];
 				return ( $comment->loaded and ($comment->user_id == $user->id or $user->has_role('admin')));
 			}
 			else
 			{
 				return false;
 			}
 		}
 		
    }
    
    // récupère les notifications
    //
	public function get_notifications ( $user_id )
	{
		return ORM::factory('comment_notification')
			->where('topic_id', $this->id)
			->where('user_id', $user_id)
			->find_all();
	}
	
	// supprime un sujet
	public function delete()
	{
		foreach($this->comments as $comment) { $comment->delete(); }
		
		parent::delete();
	}
 
}
