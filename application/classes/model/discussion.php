<?php defined('SYSPATH') or die('No direct script access.');

class Model_Discussion extends ORM {
	
	protected $_has_many = array('messages' => array());
	
	/**
	 * Vérifie sur le joueur a le droit de lecture/écriture sur un sujet
	 * 
	 * @param $user object 
	 * @param $action string r|w
	 * @return boolean 
	 */
	public function allow($user, $action='r') 
	{
		if ($action == 'r')
		{
			return TRUE;
		}
		
		if ($action == 'w')
		{
			if ($user === FALSE)
			{
				return FALSE;
			}
			else
			{
				$messages = $this->messages->find_all();
				return ($messages[0]->user_id == $user->id);
			}
		}
	}

	/**
	 * Créée une discussion
	 * 
	 * @param $user object 
	 * @param $post object 
	 * @return void 
	 */
	public function create_discussion($user, $post)
	{
		$this->type = $post['type'];
		$this->title = $post['title'];
		$this->created = time();
		$this->updated = time();
		$this->save();

		// Création du premier message
		$message = ORM::factory('message');
		$message->create_message($this->id, $user, $post);
	}
	
	/**
	 * Supprime une discussion
	 * 
	 * @return void 
	 */
	public function delete()
	{
		$this->messages->delete_all();
		
		parent::delete();
	}
 
}
