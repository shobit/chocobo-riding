<?php defined('SYSPATH') OR die('No direct access allowed.');

// Topic Controller - Gestion des topics
class Topic_Controller extends Template_Controller 
{

	// liste tous les sujets du forum
	public function index()
	{
		
		$this->template->content = View::factory('topics/index')
			->bind('user', $user)
			->bind('topics', $topics);
			
		$user = $this->session->get('user');
		
		$types = array('added', 'new');

		$topics = ORM::factory('topic')
			->where('archived', FALSE)
			->orderby('updated', 'DESC')
			->find_all();
	}
	
	// vue d'un sujet
	public function view ( $id, $page = NULL, $num = 1) 
	{
		$this->template->content = View::factory('topics/view')
			->bind('topic', $topic)
			->bind('comments', $comments)
			->bind('nbr_comments', $nbr_comments)
			->bind('user', $user)
			->bind('pagination', $pagination);
		
		$user = $this->session->get('user');
		
		$topic = ORM::factory('topic', $id);
		
		if (! $topic->loaded or ! $topic->allow($user, 'r')) 
		{
			url::redirect('topics');
    	}	    	
		
		$comments_per_page = Kohana::config('topic.comments_per_page');
		
		$comments = ORM::factory('comment')
			->where('topic_id', $topic->id)
			->find_all($comments_per_page, ($num-1)*$comments_per_page + 1);
		$nbr_comments = $this->db->count_last_query() - 1;
			
		$pagination = new Pagination(array(
		    'uri_segment' 		=> 'page', 
		    'total_items' 		=> $nbr_comments,
		    'items_per_page' 	=> $comments_per_page, 
		    'style' 			=> 'punbb'
		));
		
	}
	
	// ajoute ou édite un sujet
	public function edit ( $id = 0 ) 
	{
		$this->template->content = View::factory('topics/edit')
			->bind('user', $user)
			->bind('form', $form)
			->bind('errors', $errors);
		
		$user = $this->session->get('user');
		
		$topic = ORM::factory('topic', $id);
		$comment = ($topic->loaded) ? $topic->comments[0]: ORM::factory('comment');
		
		if ( ! $topic->allow($user, 'w')) 
		{
			url::redirect("topics");
		}
		
		$form['topic'] = $topic->as_array();
		$form['comment'] = $comment->as_array();
		
		$errors = $form;
		
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
    	    $post->add_rules('title', 'required');
            $post->add_rules('content', 'required');
            
        	if ($post->validate()) 
        	{
        		// TOPIC
        		$topic->type = $post->type;
                $topic->title = $post->title;
                
                if ($user->has_role(array('modo', 'admin')))
                {
        			$topic->locked = isset($post->locked);
                }
                
                if ( ! $topic->loaded) 
                {
                	$topic->created = time();
                }
                if ($id == 0 or $comment->user_id == $user->id) $topic->updated = time();
                
                $topic->save();
				
				// COMMENT
				$comment->content = $post->content;
				if ($comment->user_id == $user->id) $comment->updated = time();
				
				if ( ! $comment->loaded) 
				{
					$comment->topic_id = $topic->id;
					$comment->user_id = $user->id;
					$comment->created = time();
				}
				
				$comment->save();
				
				// notifications
				if ($id == 0) 
				{	
					$comment->notify();
				}
				
				// redirection
                 url::redirect('topics/' . $topic->id);
            } 
            else 
            {
            	$form = arr::overwrite($form, $post->as_array());
	        	$errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
	}
	
	/**
	 * Archive un sujet
	 *
	 * @return mixed
	 */
	public function delete ()
	{
		$user = $this->session->get('user');
		
		$errors = array();
		
		$id = $this->input->post('id', 0);
		
		$topic = ORM::factory('topic', $id);
			
		if ( ! $topic->loaded)
		{
			$errors[] = 'topic_not_found';
		}
		
		if ( ! $topic->allow($user, 'w'))
		{
			$errors[] = 'user_not_allowed';
		}
		
		if (empty($errors))
		{
			$this->db->delete('comment_notifications', array('topic_id' => $id));
    	
    		$topic->archived = TRUE;
			$topic->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			url::redirect('topics');
		}
		else
		{
			$res['success'] = empty($errors);
			$res['errors'] = $errors;
			echo json_encode($res);
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}
}
