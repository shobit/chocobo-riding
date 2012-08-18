<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_User extends Controller_Template {

	/**
	 * Vue connexion
	 * ACTION: connexion
	 */
	public function action_login() 
	{
		$this->authorize('logged_out');

		$post = Validation::factory($_POST)
			->rule('username', 'not_empty')
			->rule('password', 'not_empty');

		if ($_POST) 
		{
			$values = $_POST;
		}

		if ($_POST AND $post->check())
		{
			if (Auth::instance()->login($post['username'], $post['password']) === TRUE)
			{
				$user = Auth::instance()->get_user();

				if ($user->banned > 0)
				{
					Jgrowl::add(__('Votre compte a été banni.'));
					
					Auth::instance()->logout();
				}
				else if ($user->deleted > 0)
				{
					Jgrowl::add(__('Votre compte a été supprimé.'));
					
					Auth::instance()->logout();
				}
				else
				{
					Jgrowl::add(__('Bienvenue sur Chocobo Riding !'));

					$this->request->redirect('about#/changelog');
				}
			}
			else 
			{
				Jgrowl::add(__('Vos identifiants ne correspondent pas.'));
			}
		}

		$this->template->content = View::factory('users/login')
			->bind('values', $values);
	}
	
	/**
	 * ACTION: déconnexion
	 */
	public function action_logout() 
	{
		$this->authorize('logged_in');

		Jgrowl::add(__('A bientôt sur Chocobo Riding !'));
		
		Auth::instance()->logout();

		Request::current()->redirect('home');
	}
	
	/**
	 * Vue des jockeys
	 */
	public function action_index()
	{
		$this->authorize('logged_in');

		$u = Auth::instance()->get_user();

		$users = Model_User::get_users();

		$this->template->content = View::factory('users/index')
			->set('users', $users)
			->set('u', $u);
	}

	/**
	 * Vue d'un jockey
	 *
	 * @param $id int ID du jockey
	 * @param $section string Section à afficher
	 */
	public function action_view() 
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		$section = $this->request->param('section');

		$u = Auth::instance()->get_user();
		$c = Session::instance()->get('chocobo');

		$user = ORM::factory('user', $id);
		
		if ( ! $user->loaded())
		{
			$this->request->redirect('users');
		}

		$this->template->content = View::factory('users/view')
			->set('user', $user)
			->set('u', $u)
			->set('c', $c)
			->set('section', $section);
	}

	/**
	 * ACTION: 
	 * - évoluer l'écurie
	 * - évoluer l'inventaire
	 * - évoluer la boutique
	 * 
	 * @param $id int ID du joueur
	 * @param $apt string (boxes|inventory|shop)
	 */
	public function action_boost()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		$apt = $this->request->param('apt');

		$user = Auth::instance()->get_user();

		if ($user->id === $id)
		{
			$msg = $user->boost($apt);

			Jgrowl::add($msg);
		}

		$this->request->redirect('shop');
	}
	
	/**
	 * Vue édition du jockey en session
	 */
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
	
	/**
	 * Vue inscription (public)
	 * ACTION: inscription (public)
	 */
	public function action_register() {
		$this->authorize('logged_out');

		$user = ORM::factory('user');
		
		$post = Validation::factory($_POST)
			->rule('username', 'not_empty')
			->rule('username', 'Valid::alpha_dash')
			->rule('username', array($user, 'unique'), array('username', ':value'))
			->rule('password', 'not_empty')
			->rule('password_again', 'matches', array(':validation', ':field', 'password'))
			->rule('email', 'Valid::email')
			->rule('email', array($user, 'unique'), array('email', ':value'));

		if ($_POST)
		{
			$values = $_POST;
		}

		if ($_POST AND $post->check())
		{
			$user->register($post);
			
			//gen::add_jgrowl(Kohana::lang('jgrowl.register_success'));

			Auth::instance()->login($post['username'], $post['password']);

			$this->request->redirect('about#/changelog');
		}
		
		$errors = $post->errors('user');
		
		$this->template->content = View::factory('users/register')
			->bind('post', $post)
			->bind('values', $values)
			->bind('errors', $errors);
	}
	
	/**
	 * Vue récupérer son mot de passe et/ou lien d'activation (public)
	 * 
	 * @param $type int Type de donnée à récupérer
	 */
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
	
	/**
	 * ACTION: activer son addresse email actuelle
	 * 
	 * @param $hash string 
	 */
	public function action_verify() 
	{
		$hash = $this->request->param('hash');

		$this->auto_render = FALSE;
		$this->response->body('Ressayez plus tard...');

		return;

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
		} 
		else 
		{
			gen::add_jgrowl(Kohana::lang('jgrowl.activate_failed'));
			url::redirect('home');
		}
	}
	
	/**
	 * ACTION: supprime le joueur en session (privé)
	 */ 
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