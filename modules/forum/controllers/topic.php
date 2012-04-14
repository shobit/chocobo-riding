<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Topic Controller - Gestion des topics
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Topic_Controller extends Template_Controller 
{

	/**
	 * Index all categories & specific category
	 * 
	 * @access public
	 * @param string $type. (default: 'all')
	 * @param mixed $page. (default: null)
	 * @param int $num. (default: 1)
	 * @return void
	 */
	public function index($category='all', $page=null, $num=1) 
	{
		$user = $this->session->get('user');
		
		// pagination --------------- <<
		$page_name = Kohana::config('forum.page_name');
		$topics_per_page = Kohana::config('forum.topics_per_page');
		$pagination_format = Kohana::config('forum.pagination_format');
		
		$this->db->select('t.id');
		$this->db->from('topics AS t');
		if ($category != 'shared')
		{
			$this->db->open_paren();
			if ( ! in_array($category, array('all','locked','archived'))) 
				$this->db->where('t.type', $category);
			$this->db->where('t.shared', 0);
			if ($category == 'locked') $this->db->where('t.locked', 1);
			$this->db->where('t.archived', ($category=='archived'));
			$this->db->close_paren();	
		}
		if ($user->loaded)
		{
			// private topics
			if ($category=='all' or $category=='shared')
			{
				$this->db->join('flows AS f', 
					array(
						't.id' => 'f.topic_id'
					), null, 'LEFT');
				$this->db->open_paren();
				$this->db->orwhere('f.user_id', $user->id);
				$this->db->where('f.deleted!=', 1);
				$this->db->close_paren();
			}
						
			// favorites
			$this->db->join('favorites AS fav', 
				array(
					't.id' => 'fav.topic_id'
				), null, 'LEFT');
			$this->db->orderby('fav.position', 'DESC');
		}
		$this->db->orderby('t.updated', 'DESC');
		$this->db->limit($topics_per_page, ($num-1)*$topics_per_page);
		$topics = $this->db->get();
		$nbr_topics = $this->db->count_last_query();
		
		$pagination = new Pagination(array(
	  		'uri_segment' 		=> $page_name, 
	    	'total_items' 		=> $nbr_topics, 
	    	'items_per_page' 	=> $topics_per_page, 
	    	'style' 			=> $pagination_format
		));
		// >>
    	
    	// view
		$view = new View('topics/index');
		$view->user = $user;
		$view->topics = $topics;
		$view->category = $category;
		$view->nbr_topics = $nbr_topics;
		$view->pagination = $pagination;
		$this->template->content = $view;
	}
	
	/**
	 * View a topic
	 * 
	 * @access public
	 * @param mixed $id
	 * @param mixed $page. (default: null)
	 * @param int $num. (default: 1)
	 * @return void
	 */
	public function view($id, $page=null, $num=1) 
	{
		// user
		$user = $this->session->get('user');
		
		// topic
		$topic = ORM::factory('topic', $id);
		
		// access
		if ( ! $topic->allow($user, 'r')) url::redirect('forum');
    	
    	// deleting topic notifications
		if ($user->loaded) $topic->delete_notifs($user);
		
		// form
		$form = array(
			'content' => ''
		);
		$errors = $form;
		
		// new comment
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('content', 'required');
        	
        	if ($post->validate()) 
        	{
				$comment = ORM::factory('comment');
				$comment->topic_id = $id;
				$comment->user_id = $user->id;
				$comment->content = $post->content;
				$comment->save();
				
				$comment->topic->updated = time();
				$comment->topic->save();
				
				// update flows
				foreach ($comment->topic->flows as $flow) 
				{
					$flow->deleted = 0;
					$flow->save();
				}
				
				// create topic notifications
				$topic->add_notifs();
				
				// redirect
				$link = ($topic->type == 'private') ? "letter/" : "forum/topic/";
				url::redirect($topic->get_url_last_comment($link));
            } 
            else 
            {
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
		
		// pagination
		$page_name = Kohana::config('forum.page_name');
		$nbr_comments = ORM::factory('comment')->where('topic_id', $id)->count_all();
		$comments_per_page = Kohana::config('forum.comments_per_page');
		$pagination_format = Kohana::config('forum.pagination_format');
		
		$pagination = new Pagination(array(
		    'uri_segment' 		=> $page_name, 
		    'total_items' 		=> $nbr_comments, 
		    'items_per_page' 	=> $comments_per_page, 
		    'style' 			=> $pagination_format
		));
		
		$comments = ORM::factory('comment')
			->where('topic_id', $id)
			->find_all($comments_per_page, ($num-1)*$comments_per_page);
			
		$view 						= new View('topics/view');
		$view->topic 				= $topic;
		$view->comments 			= $comments;
		$view->user 				= $user;
		$view->form 				= $form;
		$view->pagination 			= $pagination;
		$this->template->content 	= $view;
	}
	
	/**
	 * Edit a topic
	 * 
	 * @access public
	 * @param int $id. (default: 0)
	 * @return void
	 */
	public function edit($id=0, $type="discussion") 
	{
		// user
		$user = $this->session->get('user');
		
		// topic
		$topic = ORM::factory('topic', $id);
		$comment = ORM::factory('comment');
		
		// access
		if ( ! $topic->allow($user, 'w')) url::redirect("forum");
		
		// form
		$form = array(
			'user_id' 		=> '',
			'title' 		=> '',
			'version' 		=> '',
			'type' 			=> '',
			'users' 		=> '',
			'shared' 		=> '',
			'update' 		=> '',
			'locked' 		=> '',
			'archived' 		=> '',
			'deleted' 		=> '',
			'content' 		=> ''
		);
		$errors = $form;
		$form = arr::overwrite($form, $topic->as_array());
		
		// special default values
		if ($id > 0) 
		{
			$comment = $topic->comments[0];
			$form['user_id'] = $comment->user_id;
			$form['content'] = $comment->content;
			$form['shared'] = $topic->shared;
			$form['locked'] = $topic->locked;
			$form['archived'] = $topic->archived;
			
		}
		else 
		{
			$form['user_id'] = $user->id;
			$form['type'] = $type;
		}
		
		// edit topic
		if ($_POST) 
		{
			$post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
            $post->add_rules('title', 'required');
            $post->add_rules('content', 'required');
            
            // private conditions
            if ($topic->shared or isset($post->shared)) 
            {
            	$post->add_rules('users', 'required');
            	$post->add_callbacks('users', array($this, '_valid_users'));
            }
            
            // update conditions
            if ($user->has_role('admin') and isset($post->update))
            	$post->add_rules('version', 'required');
        	
        	if ($post->validate()) 
        	{
        		// delete operations
        		if (isset($post->deleted) and $topic->loaded and $user->has_role('admin')):
        			$this->delete($topic->id);
        			url::redirect('forum');
        		endif; 
        		
        		// lock & archive operations
        		if ($topic->loaded and $user->has_role(array('modo', 'admin'))):
        			$topic->locked = (isset($post->locked) or isset($post->archived));
                	$topic->archived = (isset($post->archived));
                endif;
                
                // notification
                $notified = ( ! $topic->loaded);
                
                // new topic
        		$topic->type = $post->type;
                $topic->shared = (isset($post->shared));
                $topic->title = $post->title;
                if ( ! $topic->loaded) $topic->updated = time();
                $topic->save();
				
				// new comment
				$comment->topic_id = $topic->id;
				if ($id == 0) $comment->user_id = $form['user_id'];
				$comment->content = $post->content;
				$comment->save();
				
				// {admin} update operations
				if ($user->has_role('admin') and isset($post->update)): 
					$site = ORM::factory('site', 1);
					$site->version_link = 'forum/topic/'.$topic->id;
					$site->version_number = $post->version;
					$site->save();
					$this->db->update('users', array('version' => 0), array('version' => 1));
				endif;
				
				// private operations
				ORM::factory('flow')->where('topic_id', $topic->id)->delete_all();
   				if ((isset($post->shared)) and ! $topic->loaded) 
				{
					$user_ids = $post->users;
					$user_ids[] = $form['user_id'];
					foreach ($user_ids as $user_id) 
					{
						$flow = ORM::factory('flow');
						$flow->user_id = $user_id;
						$flow->topic_id = $topic->id;
						$flow->deleted = 0;
						$flow->save();
					}
				}
				
				// create topic notification
				if ($notified) $topic->add_notifs();
				
				// redirection
                url::redirect("forum/topic/".$topic->id);
            } 
            else 
            {
                $form = arr::overwrite($form, $post->as_array());
                $form['shared'] = (isset($post->shared));
                $form['update'] = (isset($post->update));
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
		
		// list_types
		$types = Kohana::lang('forum.type');
		foreach ($types as $value => $name) 
			if ($value != 'all' and (($value != 'update' and $value != 'announce') or $user->has_role('admin')) )
				$list_types[$value] = $name;
		
		// Construction de la vue
		$view 						= new View('topics/edit');
		$view->user 				= $user;
		$view->topic 				= $topic;
		$view->list_types 			= $list_types;
		$view->form 				= $form;
		$view->errors 				= $errors;
		$this->template->content 	= $view;
	}
	
	/**
	 * Delete a topic
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function delete($id)
    {
    	$this->authorize('modo');
    	
    	$user = $this->session->get('user');
    	$topic = ORM::factory('topic', $id);
    	
    	// nbr_flows
    	$nbr_flows = ORM::factory('flow')
    		->where('topic_id', $id)
   			->where('deleted', 0)
   			->count_all();
   		
   		// self delete
   		if ($nbr_flows >= 2)
   		{
   			$this->db->update(
   				'forum_flows', // DATABASE TOFIX
   				array('deleted' => 1),
   				array(
   					'user_id' => $user->id, 
   					'topic_id' => $id
   				)
   			);
   		}
   		// all delete
   		else
   		{
   			$topic->delete();
   		}
   		
    	// Redirection
    	url::redirect('forum');
    }
	
	/**
	 * Checks topic participants
	 * 
	 * @access public
	 * @param mixed Validation $array
	 * @param mixed $field
	 * @return void
	 */
	public function _valid_users(Validation $array, $field) 
	{
        $res = true;
        foreach ($array[$field] as $user_id) 
        { 
        	$user = ORM::factory('user')->find($user_id);
        	if ( ! $user->loaded) 
        	{
        		$res = false;
        	}
        }
        if (!$res) 
        {
        	$array->add_error($field, 'users_not_valid');
        }
    }
    
    /**
     * Favorite a topic
     * 
     * @access public
     * @param mixed $id
     * @return void
     */
    public function favorite($id)
	{
		$user = $this->session->get('user');
		$topic = ORM::factory('topic', $id);
		
		// access
		$sucess = false;
		if ($user->loaded and $topic->loaded and $topic->allow($user, 'r'))
		{
			$success = true;
			$fav_current = ORM::factory('favorite')
				->where('topic_id', $id)
				->where('user_id', $user->id)
				->find();
				
			if ( ! $fav_current->loaded)
			{
				$fav_current->topic_id = $id;
				$fav_current->user_id = $user->id;
				$fav_current->position = 1;
				$fav_current->save();
				$image = "new";
			}
			else
			{
				$fav_current->delete();
				$image = "empty";
			}
			
		}
		
		// ajax ?
		if (!request::is_ajax()) 
		{
			url::redirect('forum');
		}
		else
		{
			$res['success'] = $success;
			$res['image'] = "images/forum/star-$image.png";
			echo json_encode($res);
			
			$this->auto_render = false;
            $this->profiler->disable();
            header('content-type: application/json');
		}
		
	}
    
    /**
     * Autocompletion for 'private' topic
     * 
     * @access public
     * @return void
     */
    public function autocompletion()
    {
    	$user = $this->session->get('user');
    	
    	$users = ORM::factory('user')
    		->where('username !=', $user->username)
    		->orderby('username', 'asc')
    		->find_all();
    	
    	$response = array();
    	
    	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

		foreach ($users as $user)
		{
			$username = $user->username;
			$response[] = array(
				'caption'=>$username, 
				'value'=>$user->id, 
			);
		}
    	
    	header('content-type: application/json');
    	$this->auto_render = false;
    	$this->profiler->disable();
		echo json_encode($response);
    }
    
    /**
     * Generate 'update' feed (rss)
     * 
     * @access public
     * @return void
     */
    public function rss_updates()
	{
		$info = array(
			'title' 		=> 'Chocobo Riding - Mises à jour',
			'link'			=> 'http://chocobo-riding.menencia.com',
			'description' 	=> "Fil RSS des mises à jour de Chocobo Riding.");
		$items = array();
		
		$topics = ORM::factory('topic')
			->where('type', 'update')
			->orderby('created', 'desc')
			->find_all(20);
		
		foreach ($topics as $topic)
		{
		   	$comment = $topic->comments[0];
		   	$textile = new Textile;
			$content = $textile->TextileThis($comment->content);
		   	
		   	$items[] = array(
		   		'title'			=> $topic->title,
		    	'link' 			=> 'http://chocobo-riding.menencia.com/forum/topic/'.$topic->id,
		    	'guid' 			=> 'http://chocobo-riding.menencia.com/forum/topic/'.$topic->id,
		    	'description' 	=> $content,
		    	//'author' 		=> $post->user->username,
		    	'pubDate' 	=> date('D\, j M Y H\:i\:s ', $topic->created)."+0400"
		    );
		}
		
		echo feed::create($info, $items);
		$this->profiler->disable();
        $this->auto_render = false;
        header("content-type: application/xml");
	}
	
}
