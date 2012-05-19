<?php defined('SYSPATH') or die('No direct script access.');

class Comment_Model extends ORM {

	protected $belongs_to = array('topic', 'user');
	
	protected $has_many = array('comment_notification');
	
	// notifie les joueurs du commentaire
	public function notify () 
	{
		$users = ORM::factory('user')
			->where('id !=', $this->user_id)
			->where('activated >', 0)
			->where('banned', 0)
			->where('deleted', 0)
			->find_all();
		foreach ($users as $user) 
		{
			$comment_notification = $this->get_notification($user->id);
			if ( ! $comment_notification->loaded)
			{
				$comment_notification->topic_id = $this->topic->id;
				$comment_notification->comment_id = $this->id;
				$comment_notification->user_id = $user->id;
				$comment_notification->created = time();
				$comment_notification->save();
			}
		}
	}
	
	// récupère la notification
    //
	public function get_notification ( $user_id )
	{
		return ORM::factory('comment_notification')
			->where('comment_id', $this->id)
			->where('user_id', $user_id)
			->find();
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
		$this->db->delete('comment_notifications', array('comment_id' => $this->id));
		
		parent::delete();
	}

}
