<?php defined('SYSPATH') or die('No direct script access.');

class Model_Message extends ORM {

	protected $_belongs_to = array('discussion' => array(), 'user' => array());
	
	protected $_has_many = array(
		'message_notifications' => array(),
		'user_favorites' => array('model' => 'user', 'through' => 'messages_users'),
	);

	/**
	 * Crée un nouveau message pour une discussion
	 * 
	 * @param $discussion_id int ID de la discussion
	 * @param $user object 
	 * @param $post array 
	 * @return string URL du message
	 */
	public function create_message($discussion_id, $user, $post)
	{
		$this->discussion_id = $discussion_id;
		$this->user_id = $user->id;
		$this->content = $post['content'];
		$this->created = time();
		$this->updated = time();
		$this->save();
		
		$this->discussion->updated = time();
		$this->discussion->save();
		
		$this->notify();
		
		return $this->url();
	}

	/**
	 * Edite le contenu du message
	 * 
	 * @param $post array 
	 * @return void
	 */
	public function update_content($post)
	{
		$this->content = $post['content'];
		$this->updated = time();
		$this->save();
	}
	
	/**
	 * Notifie les joueurs du message
	 */
	public function notify() 
	{
		$users = ORM::factory('user')
			->where('id', '!=', $this->user_id)
			->where('activated', '>', 0)
			->where('banned', '=', 0)
			->where('deleted', '=', 0)
			->find_all();
		
		foreach ($users as $user) 
		{
			$message_notification = ORM::factory('message_notification');

			$message_notification->create_message_notification($user, $this);
		}
	}
	
	/**
	 * Détruit la notification si elle existe
	 *
	 * @param $user object 
	 * @return string
	 */
	public function delete_notification($user)
	{
		if ( ! $user) return;

		$mn = $user->message_notifications
			->where('message_id', '=', $this->id)
			->find();

		if ($mn->loaded())
		{
			$mn->delete();
			return ' not_seen';
		}

		return '';
	}
	
	/**
	 * Retourne l'URL du message
	 * 
	 * @return string 
	 */
	public function url()
	{
		$nbr_messages = $this->discussion->messages
			->where('id', '<', $this->id)
			->count_all();
		
		$pos = $nbr_messages + 1;
		
		$messages_per_page = 15;
		
		$page = ceil($pos / $messages_per_page);
		
		if ($page == 1)
		{
			return 'discussions/' . $this->discussion_id . '#message' . $this->id;
		}
		else
		{
			return 'discussions/' . $this->discussion_id . '/page/' . $page . '#message' . $this->id;
		}
	}
	
	/**
	 * Supprime un message
	 * 
	 * @return void 
	 */
	public function delete()
	{
		//$this->db->delete('comments_favorites', array('comment_id' => $this->id));
		
		$this->message_notifications->delete_all();
		
		parent::delete();
	}

}
