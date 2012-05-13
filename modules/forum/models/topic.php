<?php defined('SYSPATH') or die('No direct script access.');

class Topic_Model extends ORM {
    
    protected $has_many = array('comments');
    
    protected $has_and_belongs_to_many = array('tags');
	
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
 				return ( $comment->loaded and $comment->user_id == $user->id );
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
		return $this->db
			->select('DISTINCT(cn.comment_id)')
			->from('comments_notifications AS cn, comments AS c, topics AS t')
			->where('cn.user_id', $user_id)
			->where('cn.comment_id = c.id')
			->where('c.topic_id', $this->id)
			->orderby('c.updated', 'asc')
			->get();
	}
	
	// gestion des tags (formulaire)
	public function display_form_tags ()
	{
		$tags = '';
		if ($this->loaded)
		{
			$tags = array();
			foreach ($this->tags as $tag)
			{
				$tags[] = $tag->name;
			}
			$tags = implode(', ', $tags);
		}
		return $tags;
	}
	
	// gestion des tags (vue)
	public function display_view_tags ()
	{
		$tags = '';
		if ($this->loaded)
		{
			foreach ($this->tags as $tag)
			{
				$tags .= html::anchor('topics/search/tags/' . $tag->ref, '#' . $tag->name, array('class' => 'tag'));
			}
		}
		return $tags;
	}
	
	// supprime un sujet
	public function delete()
	{
		foreach($this->comments as $comment) { $comment->delete(); }
		
		$this->db->delete('tags_topics', array('topic_id' => $this->id));
		
		parent::delete();
	}
 
}
