<?php defined('SYSPATH') OR die('No direct access allowed.');

class Message_Controller extends Template_Controller 
{
	// ajouter un nouveau message
	public function add ()
	{
		$user = $this->session->get('user');
		
		if ($user->loaded and $_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('content', 'required');
            $post->add_rules('discussion_id', 'required');
        	
        	if ($post->validate()) 
        	{
				$message = ORM::factory('message')->add($post->discussion_id, $user->id, $post->content);
				
				// update flows
				foreach ($message->discussion->flows as $flow) 
				{
					$flow->deleted = 0;
					$flow->save();
				}
				
				// envoie les notifications
				$message->notify();
				
				url::redirect($message->url());
			}
            
        }
		
		url::redirect('discussions');
	}
	
}
