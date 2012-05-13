<?php defined('SYSPATH') or die('No direct script access.');

// Topic Model
class Discussion_Model extends ORM {
    
    protected $has_many = array('messages', 'flows');
    
    // Renvoie le destintaire d'une conversation
	public function receiver ( $user_id ) 
    {
		return ORM::factory('flow')
    		->where('user_id !=', $user_id)
    		->where('discussion_id', $this->id)
    		->find()
    		->user;
    }
    
    // Vérifie si le joueur a le droit de lecture/écriture sur la discussion
    public function allow ( $user, $action = 'r' ) 
    {
    	if ($action == 'r')
    	{
	    	if (count($this->flows) > 0)
	    	{ 
	    		$nbr_flows = ORM::factory('flow')
	    			->where('topic_id', $this->id)
	    			->where('user_id', $user->id)
	    			->count_all();
	    		return ($nbr_flows >= 1);
	    	}
	    	else 
	    	{
	    		return true;
	    	}
 		}
 		
 		if ($action == 'w')
 		{
 			if ($user->loaded and ! $this->loaded) // Si la discussion n'existe pas
 			{
 				return true;
 			}
 			else if ($user->loaded and $this->loaded) // ou qu'elle existe
 			{
 				$comment = $this->comments[0];
 				return ($comment->loaded and $comment->user_id == $user->id);
 			}
 			else
 			{
 				return false;
 			}
 		}
 		
    }
    
    // récupère les notifications
	public function get_notifications ( $user_id )
	{
		return $this->db
			->select('DISTINCT(mn.message_id)')
			->from('messages_notifications AS mn, messages AS m')
			->where('mn.user_id', $user_id)
			->where('mn.message_id = m.id')
			->where('m.discussion_id', $this->id)
			->orderby('m.created', 'asc')
			->get();
	}
	
	// supprime une discussion
	public function delete()
	{
		foreach($this->messages as $message) { $message->delete(); }
		foreach($this->flows as $flow) { $flow->delete(); }
		
		parent::delete();
	}
    
}
