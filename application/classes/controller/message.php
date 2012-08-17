<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Message extends Controller_Template {

	/**
	 * ACTION: ajoute un nouveau message à la discussion
	 */
	public function action_new()
	{
		$this->authorize('logged_in');

		$user = Auth::instance()->get_user();

		$post = Validation::factory($_POST)
			->rule('content', 'not_empty')
			->rule('discussion_id', 'not_empty');
		
		if ($_POST AND $post->check()) 
		{
			$message = ORM::factory('message');

			$url = $message->create_message($post['discussion_id'], $user, $post);
			
			$this->request->redirect($url);
		}
		
		$this->request->redirect('discussions');
	}
	
	/**
	 * AJAX: édite un message
	 * 
	 * @param $id int ID du message
	 */
	public function action_edit() 
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');

		$user = Auth::instance()->get_user();

		$message = ORM::factory('message', $id);
		
		$discussion = $message->discussion;
		
		if ( ! $discussion->allow($user, 'w')) 
		{
			$error = __('Vous ne pouvez pas éditer ce message.');
    	}
    	
		$post = Validation::factory($_POST)
			->rule('content', 'not_empty');

		if ($post->check()) 
		{
			$message->update_content($post);
		} 
		else
		{
			$error = __('Le contenu du message ne doit pas être vide.');
		}
		
		if ($this->request->is_ajax()) 
		{
			if (isset($error))
			{
				$res = array(
					'success' => FALSE,
					'error' => $error,
				);
			}
			else
			{
				$res = array(
					'success' => TRUE,
					'text' => Markdown::instance()->transform($message->content),
					'date' => Date::display($message->updated),
				);
			}
			
			//header('content-type: application/json');
			$this->auto_render = FALSE;
		
			//echo json_encode($res);
			$this->response
				->headers(array('content-type' => 'application/json'))
				->body(json_encode($res));
		}
	}
	
	// mettre un commentaire en favori
	public function favorite ( $id )
	{
		$user_id = $this->session->get('user')->id;
		$user = ORM::factory('user', $user_id);
			
		$comment = ORM::factory('c_favorite', $id);
		
		$error = '';
		
		if ( ! $user->loaded)
		{
			$error = 'user_not_found';
		}
		
		if ( ! $comment->loaded)
		{
			$error = 'topic_not_found'; 
		}
		
		if ( ! $comment->topic->allow($user, 'r'))
		{
			$error = 'private_topic';
		}
		
		if (empty($error))
		{
			if ($user->has($comment)) 
			{
				$user->remove($comment);
				$user->save();
				$icon = "empty";
			} 
			else 
			{
				$user->add($comment);
				$user->save();
				$icon = "new";
			}
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('topics/' . $comment->topic->id);
		}
		else
		{
			$res['success'] = empty($error);
			$res['error'] = $error;
			if ($res['success'])
			{
				$res['icon'] = $icon;
			}
			echo json_encode($res);
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
		
	}
	
}
