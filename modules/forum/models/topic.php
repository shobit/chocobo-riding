<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Topic Model
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Topic_Model extends ORM {
    
    // Relations entre modèles
    protected $has_many = array('comments', 'notifs', 'flows', 'favorites');
    
    /**
	 * Renvoie le nom du user qui a créé le topic
	 *
	 * @param   void		
	 * @return  string	le nom du user
	 */
    public function get_creator() 
    {
    	$comment = $this->comments[0];
    	$username = ($comment) ? $comment->username : "";
    	return $username;
    }
    
    /**
     * Checks if topic can be read or edited by $user
     * 
     * @access public
     * @param mixed $user
     * @return void
     */
    public function allow($user, $action='r') 
    {
    	if ($action == 'r'):
	    	if (count($this->flows) > 0)
	    	{ 
	    		$nbr_flows = ORM::factory('flow')
	    			->where('topic_id', $this->id)
	    			->where('user_id', $user->id)
	    			->where('deleted', 0)
	    			->count_all();
	    		return ($nbr_flows >= 1);
	    	}
	    	else 
	    	{
	    		return true;
	    	}
 		endif;
 		
 		if ($action == 'w'):
 			// topic is new
 			if ($user->loaded and $this->id==0)
 			{
 				return true;
 			}
 			// topic exists
 			elseif ($user->loaded and $this->id>0)
 			{
 				$first_comment = $this->comments[0];
 				return ( ($first_comment and $first_comment->user_id==$user->id) or $user->has_role(array('admin', 'modo')) );
 			}
 			else
 			{
 				return false;
 			}
 		endif;
 		
    }
    
    /**
     * Topic notification for $user
     * 
     * @access public
     * @param mixed $user_id
     * @return void
     */
    public function is_notified ($user) 
    {
    	$nbr = ORM::factory('notif')
    		->where('user_id', $user->id)
    		->where('topic_id', $this->id)
    		->count_all();
    	return ($nbr > 0);
    }
    
    /**
     * Get number of topic participants
     * 
     * @access public
     * @return void
     */
    public function get_nbr_users() 
    {
    	$nbr_users = ORM::factory('comment')
    		->select('DISTINCT user_id')
    		->where('topic_id', $this->id)
    		->count_all();
    	return ($nbr_users);
    }
    
    /**
     * Get last topic comment
     * 
     * @access public
     * @return void
     */
    public function get_last_comment() 
    {
    	$nbr_comments = count($this->comments);
    	$last_comment = $this->comments[$nbr_comments - 1];
    	return ($last_comment);
    }
    
    /**
     * Get last comment url (permalink)
     * 
     * @access public
     * @param string $link. (default: "forum/topic/")
     * @return void
     */
    public function get_url_last_comment() 
    {
    	$nbr_comments = count($this->comments);
    	$comment = $this->comments[$nbr_comments - 1];
    	$num = ceil($nbr_comments / Kohana::config('forum.comments_per_page'));
    	$page = ($num > 1) ? '/page/'.$num : '' ;
    	$url_last_comment = 'forum/topic/'.$this->id.$page.'#comment'.$comment->id;
    	return ($url_last_comment);
    }
    
    /**
     * Create topic notifications
     * 
     * @access public
     * @return void
     */
    public function add_notifs() 
    {
    	if ( ! $this->shared) 
    	{
	    	$users = ORM::factory('user')->select_list('id', 'notif_forum');
	    	foreach ($users as $id => $notif_forum) 
	    	{
	    		if ($notif_forum) 
	    		{
		    		$nbr = ORM::factory('notif')
		    			->where('user_id', $id)
		    			->where('topic_id', $this->id)
		    			->count_all();
		    		if ($nbr == 0) 
		    		{
		    			$topic_notif = ORM::factory('notif');
		    			$topic_notif->user_id = $id;
		    			$topic_notif->topic_id = $this->id;
		    			$topic_notif->save();
		    		}
	    		}
	    	}
    	}
    	else
    	{
    		foreach ($this->flows as $flow)
    		{
    			$nbr = ORM::factory('notif')
		    		->where('user_id', $flow->user_id)
		    		->where('topic_id', $this->id)
		    		->count_all();
		    	if ($nbr == 0) 
		    	{
		   			$topic_notif = ORM::factory('notif');
		   			$topic_notif->user_id = $flow->user_id;
		   			$topic_notif->topic_id = $this->id;
		   			$topic_notif->save();
		   		}
    		}
    	}
    }
    
    /**
     * Delete topic notifications
     * 
     * @access public
     * @param mixed $user_id
     * @return void
     */
    public function delete_notifs($user) 
    {
    	return ORM::factory('notif')
    		->where('user_id', $user->id)
    		->where('topic_id', $this->id)
    		->delete_all();
    }
    
    /**
     * delete a topic
     * 
     * @access public
     * @return void
     */
    public function delete()
    {
    	foreach ($this->comments as $comment) $comment->delete();
		foreach ($this->flows as $flow) $flow->delete();
		foreach ($this->notifs as $notif) $notif->delete();
		foreach ($this->favorites as $favorite) $favorite->delete();
		parent::delete();
    }
 
}
