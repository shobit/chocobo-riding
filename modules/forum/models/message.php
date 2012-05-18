<?php defined('SYSPATH') or die('No direct script access.');

class Message_Model extends ORM {

	protected $belongs_to = array('discussion', 'user');
	
	protected $has_many = array('message_notification');
	
	public function add ( $discussion_id, $user_id, $content)
	{
		$this->discussion_id = $discussion_id;
		$this->user_id = $user_id;
		$this->content = $content;
		$this->created = time();
		$this->save();
		
		$this->discussion->updated = time();
		$this->discussion->save();
		
		return $this;
	}
	
	// notifie les joueurs d'un message
	public function notify () 
	{
		$flows = ORM::factory('flow')
			//->where('user_id !=', $this->user_id) #BUG!
			->where('discussion_id', $this->discussion->id)
			->find_all();
			
		foreach ($flows as $flow)
		{
			$user = $flow->user;
			if ($flow->user_id == $this->user_id) { continue; }
			
			$message_notification = $this->get_notification($user->id);
			if ( ! $message_notification->loaded)
			{
				$message_notification->discussion_id = $this->discussion->id;
				$message_notification->message_id = $this->id;
				$message_notification->user_id = $user->id;
				$message_notification->created = time();
				$message_notification->save();
			}
			
			if ($flow->deleted === TRUE)
			{
				$flow->deleted = FALSE;
				$flow->save();
			}
		}
	}
	
	// récupère la notification
    //
	public function get_notification ( $user_id )
	{
		return ORM::factory('message_notification')
			->where('message_id', $this->id)
			->where('user_id', $user_id)
			->find();
	}
	
	// retourne le lien du dernier des messages non lus
	public function url ()
	{
		$nb_messages = ORM::factory('message')
			->where('discussion_id', $this->discussion_id)
			->where('id <', $this->id)
			->count_all();
		
		$pos = $nb_messages + 1;
		
		$messages_per_page = Kohana::config('discussion.messages_per_page');
		
		$page = ceil($pos / $messages_per_page);
		
		if ($page == 1)
		{
			return 'discussions/' . $this->discussion_id . '#m' . $this->id;
		}
		else
		{
			return 'discussions/' . $this->discussion_id . '/page/' . $page . '#m' . $this->id;
		}
	}
	
	// supprime un message
	public function delete()
	{
		$this->db->delete('message_notifications', array('message_id' => $this->id));
		
		parent::delete();
	}

}
