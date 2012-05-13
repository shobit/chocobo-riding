<?php defined('SYSPATH') OR die('No direct access allowed.');

class Discussion_Controller extends Template_Controller 
{

	// liste tous les messages
	public function index ( $page = NULL, $num = 1)
	{
		
		$this->template->content = View::factory('discussions/index')
			->bind('user', $user)
			->bind('discussions', $discussions)
			->bind('pagination', $pagination);
		
		$user = $this->session->get('user');
		
		$discussions_per_page = Kohana::config('discussion.discussions_per_page');
		
		$this->db->select('d.id');
		$this->db->from('discussions AS d');					
		$this->db->join('flows AS f', 
			array(
				'd.id' => 'f.discussion_id'
			), null, 'LEFT');
		$this->db->orwhere('f.user_id', $user->id);
		$this->db->where('f.deleted !=', TRUE);	
		$this->db->orderby('d.updated', 'DESC');
		$this->db->limit($discussions_per_page, ($num - 1) * $discussions_per_page);
		$discussions = $this->db->get();
		$nbr_discussions = $this->db->count_last_query();
		
		$pagination = new Pagination(array(
	  		'uri_segment' 		=> 'page', 
	    	'total_items' 		=> $nbr_discussions, 
	    	'items_per_page' 	=> $discussions_per_page, 
	    	'style' 			=> 'punbb'
		));
	}
	
	// vue d'un sujet
	public function view ( $id, $page = NULL, $num = 1) 
	{
		$this->template->content = View::factory('discussions/view')
			->bind('discussion', $discussion)
			->bind('messages', $messages)
			->bind('user', $user)
			->bind('receiver', $receiver)
			->bind('pagination', $pagination);
		
		$user = $this->session->get('user');
		
		$discussion = ORM::factory('discussion', $id);
		
		$receiver = $discussion->receiver($user->id);
        	
    	$messages_per_page = Kohana::config('discussion.messages_per_page');
		
		$messages = ORM::factory('message')
			->where('discussion_id', $discussion->id)
			->find_all($messages_per_page, ($num-1)*$messages_per_page);
		$nbr_messages = $this->db->count_last_query();
			
		$pagination = new Pagination(array(
		    'uri_segment' 		=> 'page', 
		    'total_items' 		=> $nbr_messages,
		    'items_per_page' 	=> $messages_per_page, 
		    'style' 			=> 'punbb'
		));
		
	}
	
	// ajoute une nouvelle conversation
	public function add () 
	{
		$this->template->content = View::factory('discussions/add')
			->bind('user', $user)
			->bind('discussion', $discussion)
			->bind('form', $form)
			->bind('errors', $errors);
		
		$user = $this->session->get('user');
		
		$discussion = ORM::factory('discussion');
		$message = ORM::factory('message');
		
		if ( ! $discussion->allow($user, 'w')) 
		{
			url::redirect("discussions");
		}
		
		$form['discussion'] = $discussion->as_array();
		$form['message'] = $message->as_array();
		
		$errors = $form;
		
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
    	    $post->add_rules('receiver', 'required');
            $post->add_callbacks('receiver', array($this, '_valid_receiver'));
            $post->add_rules('content', 'required');
            
            if ($post->validate()) 
        	{
        		// Recherche d'un flux déjà existant                
                $receiver_id = ORM::factory('user', array('name' => $post->receiver))->id;
                $flow = $this->db
                	->select('f1.discussion_id')
                	->from('flows AS f1')
                	->from('flows AS f2')
                	->where('f1.discussion_id = f2.discussion_id')
                	->where('f1.user_id', $user->id)
                	->where('f2.user_id', $receiver_id)
                	->get();
                	
                $flow_existed = FALSE;
                if (count($flow) == 1)
                {
                	$discussion = ORM::factory('discussion', $flow[0]->discussion_id);
                	$flow_existed = TRUE;
                }
                
                // DISCUSSION
                if ( ! $flow_existed) { $discussion->created = date('Y-m-d H:i:s'); }
                $discussion->updated = date('Y-m-d H:i:s');
                $discussion->save();
				
				// COMMENT
				$message->discussion_id = $discussion->id;
				$message->user_id = $user->id;
				$message->content = $post->content;
				$message->created = date('Y-m-d H:i:s');
				$message->save();
				
				// Destinataire
				if ( ! $flow_existed)
				{
					$user_ids[] = $user->id;
					$user_ids[] = $receiver_id;
	            	
	   				foreach ($user_ids as $user_id) 
					{
						$flow = ORM::factory('flow');
						$flow->user_id = $user_id;
						$flow->discussion_id = $discussion->id;
						$flow->deleted = 0;
						$flow->save();
					}
				}
				
				// notification
				$message->notify();
				
				// redirection
                url::redirect($message->url());
            } 
            else 
            {
	            $form = arr::overwrite($form, $post->as_array());
	            $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
	}
	
	// vérifie que le destinataire existe
	public function _valid_receiver(Validation $array, $field) 
	{
        $user = $this->session->get('user');
        $u = ORM::factory('user')
        	->where('name', $array[$field])
        	->where('name !=', $user->name)
        	->find();
        
        if ( ! $u->loaded) 
        {
        	$array->add_error($field, 'user_not_valid');
        }
    }
	
}
