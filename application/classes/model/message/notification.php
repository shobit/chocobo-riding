<?php defined('SYSPATH') or die('No direct script access.');

class Model_Message_Notification extends ORM {

	/**
	 * Crée une notification pour le message
	 *
	 * @param $user object 
	 * @param $message object 
	 * @return void 
	 */
	public function create_message_notification($user, $message)
	{
		$this->discussion_id = $message->discussion->id;
		$this->message_id = $message->id;
		$this->user_id = $user->id;
		$this->created = time();
		$this->save();		
	}
	
}
