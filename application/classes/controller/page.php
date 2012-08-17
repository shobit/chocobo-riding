<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Template {

	/**
	 * Vue présentation
	 */
	public function action_home()
	{
		$this->authorize('logged_out');

		$this->template->content = View::factory('pages/home');
	}
	
	/**
	 * Vue développeurs
	 */
	public function action_developers()
	{
		$this->authorize('logged_in');

		$user = Auth::instance()->get_user();
	
		$this->template->content = View::factory('pages/developers')
			->set('user', $user);
	}

	/**
	 * Vue de l'aide
	 */
	public function action_help()
	{
		$this->authorize('logged_in');

		$this->template->content = View::factory('pages/help');
	}

	/**
	 * Vue shoutbox
	 */
	public function action_shoutbox()
	{
		$this->authorize('logged_in');

		$this->template->content = View::factory('pages/shoutbox');
	}
	
	/**
	 * Vue shoutbox en pop-up
	 */
	public function action_shoutbox_external()
	{
		$this->authorize('logged_in');

		$this->template = View::factory('templates/shoutbox');
	}

	/**
	 * Vue à propos
	 */
	public function action_about()
	{
		$user = Auth::instance()->get_user();

		$updates = ORM::factory('update')
			->order_by('date', 'desc')
			->find_all();

		$this->template->content = View::factory('pages/about')
			->set('user', $user)
			->set('updates', $updates);
	}
	
	/**
	 * Vue maintenance
	 */
	public function action_maintenance()
	{
		$this->template->content = View::factory('pages/closed');
	}
	
	/**
	 * ACTION: changer la langue (TODO)
	 */
	public function locale($locale)
	{
		$user = $this->session->get('user');
		$languages = array_keys(gen::languages());
		if ($user->id >0)
		{
			$default = $user->locale;
			if (!in_array($locale, $languages)) $locale = $default;
			$user->locale = $locale;
			$user->save();
			
		}
		else
		{
			$default = cookie::get('locale');
			if (!in_array($locale, $languages)) $locale = $default;
			cookie::set('locale', $locale);
		}
		url::redirect(request::referrer());
	}
	
	/**
	 * ACTION: changer le design (TODO)
	 */
	public function design($design_name)
	{
		$user = $this->session->get('user');
		
		$design = ORM::factory('design')
			->where('name', $design_name)
			->find();
		if ($user->id >0)
		{
			if ($design->id >0) 
			{
				$user->design_id = $design->id;
				$user->save();
			}
		}
		else
			if ($design->id >0)
				cookie::set('design', $design_name);
					
		url::redirect(request::referrer());
	}
	
	/**
	 * Vue test
	 */
	public function action_test()
	{
		$this->authorize('admin');

		$this->template->content = View::factory('pages/test');
	}

}