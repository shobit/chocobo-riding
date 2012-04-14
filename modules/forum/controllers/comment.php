<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Post Controller - Gestion des posts
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Comment_Controller extends Template_Controller 
{
	/**
	 * Edit a comment
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function edit($id) 
	{
		$user = $this->session->get('user');
		
		$comment = ORM::factory('comment', $id);
		$topic = $comment->topic;
		
		// access
		if ( empty($id) or ! $topic->allow($user, 'w') ) url::redirect('forum');
    	
		// form
		$form = array(
			'user_id' => $user->id,
			'content' => $comment->content
		);
		$errors = $form;
		
		// edit comment
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('content', 'required');
        	
        	if ($post->validate()) 
        	{
				$comment->content = $post->content;
				$comment->save();
				
				// TODO current comment
                url::redirect($topic->get_url_last_comment());
            } 
            else 
            {
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
		
		// view
		$view 						= new View('comments/edit');
		$view->user 				= $user;
		$view->comment 				= $comment;
		$view->form 				= $form;
		$view->errors 				= $errors;
		$this->template->content 	= $view;
	}
	
	/**
	 * Edit the topic interest (ajax)
	 * 
	 * @access public
	 * @param mixed $id
	 * @param mixed $value
	 * @return void
	 */
	public function interest($id, $value)
	{	
		$this->authorize('admin');
		
		$user = $this->session->get('user');
		$interest = ORM::factory('interest')
			->where('user_id', $user->id)
			->where('comment_id', $id)
			->find();
			
		if ($value != 0)
		{
			$interest->user_id 		= $user->id;
			$interest->comment_id 	= $id;
			$interest->value 		= (in_array($value, array(-1, 0, 1))) ? $value : 0;
			$interest->save();
		}
		else
		{
			$interest->delete();
		}
		
		// ajax ?
		if (!request::is_ajax()) 
		{
			url::redirect("forum/topic/2"); // TODO
		}
		else
		{
			$somme = 0;
			$interets = ORM::factory('interest')->where('comment_id', $id)->find_all();
			foreach($interets as $interet) $somme += $interet->value;
			echo $somme;
			
			$this->auto_render = false;
            $this->profiler->disable();
            header('content-type: application/json');
		}
	}
	
}
