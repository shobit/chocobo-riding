<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Discussion extends Controller_Template {

	/**
	 * Vue des discussions publiques et privées
	 */
	public function action_index()
	{
		$user = Auth::instance()->get_user();
		
		$discussions = ORM::factory('discussion')
			->where('archived', '=', FALSE)
			->order_by('updated', 'DESC')
			->find_all();

		$this->template->content = View::factory('discussions/index')
			->set('user', $user)
			->set('discussions', $discussions);
	}
	
	/**
	 * Vue d'un sujet
	 */
	public function action_view() 
	{
		$id = $this->request->param('id');
		$page = $this->request->param('page', 1);
		
		$user = Auth::instance()->get_user();
		
		$discussion = ORM::factory('discussion', $id);
		
		if ($discussion->loaded() === FALSE OR ! $discussion->allow($user, 'r')) 
		{
			$this->request->redirect('topics');
		}	    	
		
		$messages_per_page = 15;

		$messages =	$discussion->messages->
			limit($messages_per_page)
			->offset(($page-1)*$messages_per_page + 1)
			->find_all();
		$nbr_messages = $discussion->messages->count_all() - 1;
			
		$pagination = Pagination::factory(array(
			'current_page'   => array('source' => 'route', 'key' => 'page'),
			'total_items'    => $nbr_messages,
			'items_per_page' => $messages_per_page,
			'view'           => 'pagination/basic',
		));

		$this->template->content = View::factory('discussions/view')
			->set('discussion', $discussion)
			->set('messages', $messages)
			->set('nbr_messages', $nbr_messages)
			->set('user', $user)
			->set('pagination', $pagination);
	}
	
	/**
	 * Vue pour créer une nouvelle discussion
	 */
	public function action_new()
	{
		$this->authorize('logged_in');

		$user = Auth::instance()->get_user();
		
		$discussion = ORM::factory('discussion');
		
		$post = Validation::factory($_POST)
			->rule('title', 'not_empty')
			->rule('content', 'not_empty');
		
		if ($_POST AND $post->check()) 
		{
			$discussion->create_discussion($user, $post);

			$this->request->redirect('discussions/'.$discussion->id);
		}

		$this->template->content = View::factory('discussions/new')
			->set('user', $user)
			->bind('values', $_POST);
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
