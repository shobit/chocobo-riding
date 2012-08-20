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
	 * Vue pour éditer du joueur en session
	 * 
	 * @param $id int ID du joueur
	 */
	public function action_edit() 
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');
		
		$u = Auth::instance()->get_user();
		$user = ORM::factory('user', $id);

		if ($id != $u->id)
		{
			$this->request->redirect('users/'.$user->id);
		}
		
		$post = Validation::factory($_POST);

		if ($_POST AND $post['type'] == 'avatar')
		{
			$file = Validation::factory( $_FILES )
				->rule('image', 'Upload::not_empty')
				->rule('image', 'Upload::valid')
				->rule('image', 'Upload::type', array(array('jpg', 'png', 'gif')))
				->rule('image', 'Upload::size', array('300K'));

			if ($file->check())
			{
				$filename = Upload::save($_FILES['image'], $user->id, 'upload/temp/');

				if ($filename !== FALSE) 
				{
					// Suppression des images existantes
					if ($user->image != "") 
					{
						unlink('upload/users/mini/'.$user->image);
						unlink('upload/users/thumbmail/'.$user->image);
					}

					// Nom du fichier
					$name = $user->id.'.'.substr(strrchr($filename, '.'), 1);

					// Miniature
					$image = Image::factory($filename)
						->resize(30, 30, Image::WIDTH)
						->crop(30, 30)
						->save('upload/users/mini/'.$name);

					Image::factory($filename)
						->resize(100, 100, Image::WIDTH)
						->crop(100, 100)
						->save('upload/users/thumbmail/'.$name);

					// Suppression de l'image temporaire
					unlink($filename);
					
					$user->update_image($name);

				$this->request->redirect('users/'.$user->id.'#/informations');
				}
			}
		}
		else if ($_POST AND $post['type'] == 'email')
		{
			$post = $post
				->rule('email', 'Valid::email')
				->rule('email', array($user, 'unique'), array('email', ':value'));

			if ($post->check())
			{
				$user->update_email($post['email']);

				$this->request->redirect('users/'.$user->id.'#/informations');
			}
		}
		else if ($_POST AND $post['type'] == 'password')
		{
			$post = $post
				->rule('password_old', 'not_empty')
				->rule('password_old', array(Auth::instance(), 'check_password'), array(':value'))
				->rule('password', 'not_empty')
				->rule('password_again', 'matches', array(':validation', ':field', 'password'));

			if ($post->check())
			{
				$user->update_password($post['password']);

				$this->request->redirect('users/'.$user->id.'#/informations');
			}
		}
		
		$this->template->content = View::factory('users/edit')
			->set('user', $user);
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

		$msg = Model_User::verify($hash);
		
		Jgrowl::add($msg);
		
		$this->request->redirect('home');
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