<?php defined('SYSPATH') or die('No direct script access.');

class Comment_Model extends ORM {

	protected $belongs_to = array('topic', 'user');
	
	// notifie les joueurs du commentaire
	public function notify () 
	{
		$users = ORM::factory('user')
			->where('id !=', $this->user_id)
			->find_all();
		foreach ($users as $user) 
		{
			if ( ! $user->has(ORM::factory('c_notification', $this->id)))
			{
				$user->add(ORM::factory('c_notification', $this->id));
				$user->save();
			}
		}
	}
	
	// retourne le lien du commentaire
	public function url ()
	{
		$nb_comments = ORM::factory('comment')
			->where('topic_id', $this->topic_id)
			->where('id <', $this->id)
			->count_all();
		
		$pos = $nb_comments + 1;
		
		$comments_per_page = Kohana::config('topic.comments_per_page');
		
		$page = ceil($pos / $comments_per_page);
		
		if ($page == 1)
		{
			return 'topics/' . $this->topic_id . '#comment' . $this->id;
		}
		else
		{
			return 'topics/' . $this->topic_id . '/page/' . $page . '#comment' . $this->id;
		}
	}
	
	// supprime un commentaire
	public function delete()
	{
		$this->db->delete('comments_favorites', array('comment_id' => $this->id));
		$this->db->delete('comments_notifications', array('comment_id' => $this->id));
		
		parent::delete();
	}

}
