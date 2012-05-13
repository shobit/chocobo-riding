<?php defined('SYSPATH') or die('No direct script access.');

class Message_Model extends ORM {

	protected $belongs_to = array('discussion', 'user');
	
	public function add ( $discussion_id, $user_id, $content)
	{
		$this->discussion_id = $discussion_id;
		$this->user_id = $user_id;
		$this->content = $content;
		$this->created = date('Y-m-d H:i:s');
		$this->save();
		
		$this->discussion->updated = date('Y-m-d H:i:s');
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
			
			if ( ! $user->has(ORM::factory('m_notification', $this->id)))
			{
				$user->add(ORM::factory('m_notification', $this->id));
				$user->save();
			}
			
			if ($flow->deleted === TRUE)
			{
				$flow->deleted = FALSE;
				$flow->save();
			}
		}
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
		$this->db->delete('messages_notifications', array('message_id' => $this->id));
		
		parent::delete();
	}

}
