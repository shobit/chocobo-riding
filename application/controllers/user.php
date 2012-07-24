<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Controller extends Template_Controller {

	// connexion d'un joueur
	public function login () 
	{
     	$this->template->content = View::factory('users/login')
     		->bind('errors', $errors);
	
		$this->authorize('logged_out');
     	$errors = array();
        
        if ($_POST)
        {        
	        $post = new Validation($_POST);
	        $user = ORM::factory('user')
	        	->where('username', $post->username)
	        	->find();
	        if ( ! $user->loaded or $user->password != sha1($post->password)) 
	        {
	        	$errors[] = "lost_password";	
	        }
	        else if ($user->activated == 0)
	        {
	        	$errors[] = "lost_activation";
	        }
	        else if ($user->banned > 0)
	        {
	        	$errors[] = "banned_account";
	        }
	        else if ($user->deleted > 0)
	        {
	        	$errors[] = "deleted_account";
	        }
	        else 
            {
            	$this->session->set('user', $user);
        		if (isset($post->remember)) 
        		{
        			cookie::set('username', $user->username, 7*24*3600);
        			cookie::set('password', $user->password, 7*24*3600);
        		}
        		url::redirect('page/events');
        	}
        }
    }
	
	// FUNC: déconnexion du user en session
	public function logout() 
	{
		$this->authorize('logged_in');
		gen::add_jgrowl(Kohana::lang('jgrowl.logout_success'));
        cookie::delete('username');
        cookie::delete('password');
        $this->session->delete('user', 'chocobo');
		url::redirect('home');
	}
	
	/*
	 * vue de la fiche d'un user
	 * var $username STRING
	 */
	public function view ( $id ) 
	{
		$this->template->content = View::factory('users/view')
			->bind('user', $user)
			->bind('u', $u);
	
		$u = $this->session->get('user');
		
		$user = ORM::factory('user', $id);
		
		if ( ! $user->loaded)
		{
	    	url::redirect('users');
		}
	}

	public function upgrade_boxes()
	{
		$this->authorize('logged_in');

		$user = $this->session->get('user');
		$gils = $user->gils;
		
		$cost = $user->get_boxes_cost();

		if ($gils < $cost)
		{
			$msg = 'not_enough_gils';
		}

		if ($user->boxes >= 5)
		{
			$msg = 'boxes_max';
		}

		if ( ! isset($msg)) 
		{
			$msg = 'Box+ acheté !';
			$user->set_gils(-$cost);
			$user->boxes++;
			$user->save();
			
			$user->listen_success(array( # SUCCES
				"boxes_3",
				"boxes_5",
				"boxes_7"
			));
		}

		gen::add_jgrowl($msg);
		url::redirect('shop');
	}

	public function upgrade_inventory()
	{
		$this->authorize('logged_in');

		$user = $this->session->get('user');
		$gils = $user->gils;
		
		$cost = $user->get_inventory_cost();

		if ($gils < $cost)
		{
			$msg = 'not_enough_gils';
		}

		if ($user->items >= 5)
		{
			$msg = 'items_max';
		}

		if ( ! isset($msg)) 
		{
			$msg = 'Inventaire+ acheté !';
			$user->set_gils(-$cost);
			$user->items++;
			$user->save();

			$user->listen_success(array( # SUCCES
				"items_12",
				"items_15",
				"items_20"
			));
		}

		gen::add_jgrowl($msg);
		url::redirect('shop');
	}

	/**
	 * Augmenter de 1 le niveau de la boutique du joueur en session
	 *
	 */
	public function upgrade_shop()
	{
		$this->authorize('logged_in');

		$user = $this->session->get('user');
		$gils = $user->gils;
		
		$cost = $user->get_shop_cost();

		if ($gils < $cost)
		{
			$msg = 'not_enough_gils';
		}

		if ($user->shop >= 5)
		{
			$msg = 'shop_max';
		}

		if ( ! isset($msg)) 
		{
			$msg = 'Boutique+ acheté !';
			$user->set_gils(-$cost);
			$user->shop++;
			$user->save();
		}

		gen::add_jgrowl($msg);
		url::redirect('shop');
	}
	
	// FUNC: éditer la fiche du user de la session en cours
	public function edit() {
		$this->authorize('logged_in');
        $user = $this->session->get('user');
        
        $errors = array();
        $message = "";
        
        // si on a cliqué sur "modifier le profil"
        if ($_POST) {
			
			// préparation des champs
			$post = new Validation($_POST);
			$post->pre_filter('trim', TRUE);
			if ( ! empty($post->password))
			{
				$post->add_rules('password_again', 'matches[password_new]');
				$post->add_callbacks('password', array($this, '_matches_password'));
			}
			
			$files = Validation::factory($_FILES)
            	->add_rules(
            		'image', 
            		'upload::valid',  
            		'upload::type[gif,jpg,png]', 
            		'upload::size[1M]');
        	
        	// enregistrement des nouvelles données
        	if ($post->validate() and $files->validate()) {
        	
        		
        		if (!empty($post->password_new)) {
	        		$user->password = sha1($post->password_new);
	                $user->updated  = time();
				}		
                	
                $user->gender = $post->gender;
                $user->birthday = $post->birthday;
                $user->locale = $post->locale;
                $user->notif_forum = $post->notif_forum;
				
                // traitement et sauvegarde de la nouvelle image
				$filename = upload::save('image');
				if (file_exists($filename)) {
					if ($user->image != "") {
						unlink('upload/users/mini/'.$user->image);
						unlink('upload/users/thumbmail/'.$user->image);
					}
					$name = $user->id.'.'.substr(strrchr($filename, '.'), 1);
					Image::factory($filename)
						->resize(30, 30, Image::WIDTH)
						->crop(30, 30)
						->save(DOCROOT.'upload/users/mini/'.$name);
					Image::factory($filename)
						->resize(100, 100, Image::WIDTH)
						->crop(100, 100)
						->save(DOCROOT.'upload/users/thumbmail/'.$name);
					unlink($filename);
					$user->image = $name;
					$user->listen_success("avatar_upload");
				}
				
				$user->save();
				
                //Redirect
                url::redirect('users/' . $user->id);
            } else {
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }  
        }
		
		$edit = new View('users/edit');
        $edit->user = $user;
        $this->template->content = $edit;
	}
	
	// FUNC: inscription d'un nouveau user & chocobo
	public function register() {
		$this->authorize('logged_out');
        $form = array (
            'username' => '',
            'password' => '',
            'password_again' => '',
            'email' => '',
        );
        
        $errors = $form;
        
        if ($_POST) {
	   
    	    $post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
    	    $post->add_rules('username', 'required', 'length[4,12]', 'alpha_dash');
            $post->add_rules('password', 'required');
            $post->add_rules('password_again', 'matches[password]'); 
    	    $post->add_rules('email', 'required', 'email');
            $post->add_callbacks('username', array($this, '_unique_username'));
            $post->add_callbacks('email', array($this, '_unique_email'));
        	
        	if ($post->validate()) {
                
                // TOVERIFY
                $to      	= $post->email;
				$from    	= 'mail@menencia.com';
				$subject 	= Kohana::lang('user.register.mail_title');
				$password 	= sha1($post->password);
				$link 		= url::site('user/activate/'.$password);
				$message 	= str_replace(
					array('%username%', '%password%', '%link%'),
					array($post->username, $post->password, $link),
					Kohana::lang('user.register.mail_content')
				);
                if (email::send($to, $from, $subject, $message, TRUE)) {
                	$user = ORM::factory('user');
	                $user->username 	= $post->username;
	                $user->password 	= $password;
	                $user->email 		= $post->email;
	                $user->locale 	= cookie::get('locale');
	                $user->design_id	= 1;
	                $user->version 	= true;
	                $user->created = time();
	                $user->updated 	= time();
	                $user->save();
	                
	                gen::add_jgrowl(Kohana::lang('jgrowl.register_success'));
                } else {
                	gen::add_jgrowl(Kohana::lang('jgrowl.mail_not_send'));
                }
                url::redirect('home');
            } else {
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
            
        }
        
        $register = new View('users/register');       
        $register->errors = $errors;
        $register->form = $form;
        $this->template->content = $register;
	}
	
	// FUNC: Formulaire pour perte de mot de passe & lien d'activation
	// var $type = {'password', 'activation'}
	public function lost($type) 
	{
		$this->authorize('logged_out');
		$form = array (
            'username' => '',
            'email' => '',
        );
        
        $errors = $form;
        
        if ($_POST) {
	   
    	    $post = new Validation($_POST);
    	    $post->pre_filter('trim', TRUE);
    	    $post->add_rules('username', 'required', 'length[4,12]');
    	    $post->add_rules('email', 'required', 'email');
        	
        	if ($post->validate()) {
                $user = ORM::factory('user')
                			 ->where('username', $post->username)
                			 ->where('email', $post->email)
                			 ->where('banned', 0)
							 ->where('deleted', 0)
							 ->find();
                
                if ( $user->id >0 and (time() - $user->updated > 3600*7) ) {
	                $password 			= text::random();
					$password_sha1 		= sha1($password);
					$link 				= url::site('user/activate/'.$password_sha1);
					
	                // TOVERIFY
	                $to      = $post->email;
					$from    = 'mail@menencia.com';
					$subject = Kohana::lang('user.lost.mail_title');
					$message = str_replace(
						array('%username%', '%password%', '%link%'),
						array($post->username, $password, $link),
						Kohana::lang('user.lost.mail_content')
					);
	                if (email::send($to, $from, $subject, $message, TRUE)) {
	                	$user->password 	= $password_sha1;
	                	$user->updated 	= time();
						$user->save();
						gen::add_jgrowl(Kohana::lang('jgrowl.lost_success'));
					} else {
	                	gen::add_jgrowl(Kohana::lang('jgrowl.mail_not_send'));
	                }
	                url::redirect('user/lost/'.$type);
		        } else {
		        	gen::add_jgrowl(Kohana::lang('jgrowl.lost_failed'));
		        	url::redirect('user/lost/'.$type);
		        }
            } else {
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
            
        }
		
		$view = new View('users/lost');
		$view->type = $type;      
        $view->errors = $errors;
        $view->form = $form;
        $this->template->content = $view;
	}
	
	// FUNC: active le compte s'il existe, qui possède le password donné (sha1)
	// var $sha1 STRING
	public function activate($sha1) 
	{
		$this->authorize('logged_out');
		$user = ORM::factory('user')
			->where(array('password' => $sha1, 'activated' => 0, 'banned' => 0, 'deleted' => 0))
			->find();
		
		if ($user->id >0) {
			$user->activated = time();
			$user->save();
			
			$nut = ORM::factory('nut');
			$nut->gender = rand(1, 2);
			ORM::factory('chocobo')->generate($user->id, $nut);

			gen::add_jgrowl(Kohana::lang('jgrowl.activate_success'));
			url::redirect('home');
		} else {
			gen::add_jgrowl(Kohana::lang('jgrowl.activate_failed'));
			url::redirect('home');
		}
	}
	
	// FUNC:
	public function _unique_username(Validation $array, $field) {
        $username_exists = 
        	(bool) ORM::factory('user')
        		->where('username', $array[$field])
        		->count_all();
        
        if ($username_exists) {
            $array->add_error($field, 'username_exists');
        }
    }
    
	// FUNC:
	public function _matches_password(Validation $array, $field) {
        $user = $this->session->get('user');
        $matches_password = ( $user->password == sha1($array[$field]) );
        
        if ( ! $matches_password) {
            $array->add_error($field, 'matches_password');
        }
    }
    
	// FUNC:
    public function _unique_email(Validation $array, $field) {
        $email_exists = 
        	(bool) ORM::factory('user')
        		->where('email', $array[$field])
        		->count_all();
        
        if ($email_exists) {
            $array->add_error($field, 'email_exists');
        }
    }
    
    // FUNC: Supprime un user (irréversible) - TOVERIFY
    public function delete()
    {
    	$this->authorize('logged_in');
    	
    	$user = $this->session->get('user');
    	if ($user->image != "") {
			unlink('upload/users/mini/'.$user->image);
			unlink('upload/users/thumbmail/'.$user->image);
		}
		
		cookie::delete('username');
        cookie::delete('password');
        $this->session->delete('user', 'chocobo');
		
    	$user->delete();
		
		gen::add_jgrowl(Kohana::lang('jgrowl.deleted_success'));
		url::redirect('home');
    }
    
    /**
     * Autocompletion
     * 
     */
    public function autocompletion()
    {
    	$q = strtolower($_GET["q"]);
    	$items = ORM::factory('user')
    		->orderby('username', 'asc')
    		->like('username', $q)
    		->find_all();
    	
    	$this->auto_render = false;
    	$this->profiler->disable();
	
		$response = "";
    	foreach ($items as $item)
		{
			echo $item->username."\n";
		}
    }

}