<?php

class Controller extends Controller_Core
{
    // Session connection
    public $session;
    
    // DB connection
    public $db;
    
    // Actual template
    public $template = 'templates/default';
    
    // Active auto render
    public $auto_render = true;
    
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // database
        $group = (IN_PRODUCTION) ? 'prod': 'dev';
        $this->db = Database::instance($group);
        
        // session
        $this->session = Session::instance();
        $user = $this->session->get('user');
        if (empty($user)) 
        {
        	$user = ORM::factory('user');
        	$this->session->set('user', $user);
        }
        
        // design
    	$design = cookie::get('design');
    	if (empty($design)) 
        {
    		$design = 'default';
    		cookie::set('design', $design);
    	}
    	$this->session->set('design', $design);
    	
        // OPENED or ClOSED
        $site = ORM::factory('site', 1);
        if ( Router::$current_uri != 'page/closed' 
        	and Router::$current_uri != 'closed' 
        	and $site->closed ) 
        {
        	cookie::delete('username');
        	cookie::delete('password');
        	$this->session->delete('user', 'chocobo');
        	url::redirect('closed');
        }
        
        // connexion cookie
        $cookie_username = cookie::get('username');
        $cookie_password = cookie::get('password');
        if ( $user->id==0 
        	and (!empty($cookie_username)) 
        	and (!empty($cookie_password)) ) 
        {
        	$user = ORM::factory('user')
        		->where('username', $cookie_username)
        		->where('password', $cookie_password)
        		->where('activated >', 0)
        		->where('banned', 0)
        		->where('deleted', 0)
        		->find();
        	$this->session->set('user', $user);
        	gen::add_jgrowl(Kohana::lang('jgrowl.login_success'), true);
        }
        
        ///////////////////
        ///////// CONNECTED
        ///////////////////
        if ($user->loaded) 
        {
        	// locale
        	Kohana::config_set("locale", $user->locale);
        	
        	// design
        	if ($user->design_id != null)
        		$this->session->set('design', $user->design->name);
        	
        	// tutorial
        	if ($user->tutorial == 0) 
        	{
        		$link = html::anchor('tutorial', Kohana::lang('menu.tutorial'));
        		$text = Kohana::lang('jgrowl.tutorial');
        		$text = str_replace('%tutorial%', $link, $text);
        		gen::add_jgrowl($text, true);
        	}
        		
        	// version
        	if ($user->version == 0) 
        	{
        		$link = html::anchor($site->version_link, $site->version_number);
        		$text = Kohana::lang('jgrowl.version');
        		$text = str_replace('%version%', $link, $text);
        		gen::add_jgrowl($text, true);
        	}
        	
        	// mise à jour de connected
        	$user->connected = time();
        	if (!$user->tutorial) $user->tutorial = true;
        	if (!$user->version) $user->version = true;
        	$user->save();
        	
        	// succès non vus
        	$successes = ORM::factory('success')
        		->where('user_id', $user->id)
        		->where('seen', FALSE)
        		->find_all();
        	foreach ($successes as $success)
        	{
        		$success->add_jgrowl();
        		$success->seen = TRUE;
        		$success->save();
        	}
        	
        	// regarde si tous les chocobos ont leurs noms
        	$redirect = false;
        	foreach ($user->chocobos as $chocobo) 
        	{
        		$chocobo->regain();
        		//if ($chocobo->status == 2) $chocobo->baby_status();
        		
				if ( (empty($chocobo->name)) and 
					(Router::$current_uri != 'chocobo/edit') and 
					(Router::$current_uri != 'user/logout') and 
					(Router::$current_uri != 'logout') ) 
				{
					$this->session->set('chocobo', $chocobo);
					$redirect = true;
				}
			}
			if ($redirect) url::redirect('chocobo/edit');
			
			// Le chocobo existe en session (sinon on prend le premier de la liste)
			$chocobo = $this->session->get('chocobo');
			if (!$chocobo)
			{ 
				$chocobo = $user->chocobos[0];
				$this->session->set('chocobo', $chocobo);
			}
			
			// Si le chocobo en session est inscrit à une course, on la met à jour
			$chocobo->update_race();
        }
        
        ///////////////////////
        ///////// NOT CONNECTED
        ///////////////////////
        else
        {
        	// Configuration locale
        	$locale = cookie::get('locale');
        	$languages = Kohana::user_agent('languages');
        	if (empty($locale)) 
        	{
        		$locale = (!empty($languages)) ? $languages[0] : "xx_XX";
        		if (!in_array($locale, gen::languages())) $locale = "fr_FR";
        		cookie::set('locale', $locale, 3600*24*7);
        	}
        	Kohana::config_set("locale", $locale);	
        }
        	
        // démarre le profiler
		$this->profiler = new Profiler;
    	if (IN_PRODUCTION) $this->profiler->disable();
    }
    
    /**
     * Access redirection for logged_in & logged_out mode
     * 
     * @access public
     * @param mixed $status
     * @param string $url. (default: NULL)
     * @return void
     */
    public function authorize($status) 
    {
    	$user = $this->session->get('user');
    	
    	if ($status == 'logged_in' and ! $user->loaded) 
   		{
    		url::redirect('home');
    	}
    	
    	if ($status == 'logged_out' and $user->loaded) 
   		{
    		url::redirect('updates');
    	}
    	
    	if ($status == 'admin' and ! $user->has_role('admin'))
    	{
    		url::redirect('updates');
    	}
    }
    
}
