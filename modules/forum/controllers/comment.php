<?php defined('SYSPATH') OR die('No direct access allowed.');

class Comment_Controller extends Template_Controller 
{
	// ajouter un nouveau commentaire
	public function add ()
	{
		$user = $this->session->get('user');
		
		if ($user->loaded and $_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('content', 'required');
            $post->add_rules('topic_id', 'required');
        	
        	if ($post->validate()) 
        	{
				$comment = ORM::factory('comment');
				$comment->topic_id = $post->topic_id;
				$comment->user_id = $user->id;
				$comment->content = $post->content;
				$comment->created = date('Y-m-d H:i:s');
				$comment->updated = date('Y-m-d H:i:s');
				$comment->save();
				
				$comment->topic->updated = date('Y-m-d H:i:s');
				$comment->topic->save();
				
				// envoie les notifications
				$comment->notify();
				
				url::redirect($comment->url());
			}
        }
		
		url::redirect('topics');
	}
	
	// édite un commentaire AJAX
	public function edit($id) 
	{
		$user = $this->session->get('user');
		$comment = ORM::factory('comment', $id);
		$topic = $comment->topic;
		
		$error = '';
		
		if ( ! $topic->allow($user, 'w') ) 
		{
			$error = 'user_not_found';
    	}
    	
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('content', 'required');
        	
        	if ($post->validate()) 
        	{
				$comment->content = $post->content;
				$comment->updated = date('Y-m-d H:i:s');
				$comment->save();
			}
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			//url::redirect('topics/' . $comment->topic->id);
		}
		else
		{
			$res['success'] = empty($error);
			$res['error'] = $error;
			if ($res['success'])
			{
				require_once Kohana::find_file('libraries', 'markdown');
				$res['text'] = Markdown($comment->content);
				$res['date'] = date::display($comment->updated);
			}
			echo json_encode($res);
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
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
