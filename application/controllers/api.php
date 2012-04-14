<?php
class Api_Controller extends Template_Controller {

	protected $salt = "b803de0023a9ae5570a67be6fff77fc7f8c6b57727c65164c962720f06e144bc8ea85ca4c424cf5c";
	
    /////////////////////////
    ///////////////// CONNECT
    /////////////////////////
	public function connect()
    {
    	$res = array();
    	$post = new Validation($_POST);
    	if ( !empty($post->username) and 
    		!empty($post->password) and 
    		!empty($post->stamp) and 
    		!empty($post->key) ) 
    	{
	    	//if (abs ($post->stamp - time()) < 30)
	    	if (true)
	    	{
	    		$md5 = md5(
	    			$post->username . 
	    			$post->password . 
	    			$post->stamp . 
	    			$this->salt);
	    		
	    		if ($post->key == $md5)
		    	{
			    	$user = ORM::factory('user')
			        	->where('username', $post->username)
			        	->find();
			        if ($user->loaded and $user->password == sha1($post->password)) 
			        {
			            if ($user->activated) 
			            {
			            	if (empty($user->mac)) 
			            	{
			            		$mac = uniqid();
			            		$user->mac = $mac;
			            		$user->save();
			            	}
			            	else $mac = $user->mac;
			            	
			            	$res['status'] 	= 0; // Connecté
			            	$res['mac'] 	= $mac;
			            } 
			            else $res['status'] = 1; // Compte non activé
			        } 
			        else $res['status'] = 2; // Pas de correspondances
		        }
		        else $res['status'] = 3; // Clé incorrecte
	        }
	        else $res['status'] = 4; // Délai dépassé
        } 
        else $res['status'] = 5; // Données POST incomplètes
		
		echo json_encode($res);
		
		$this->make_json();
    }
    
    ////./////////////////////////
    ///////////////// JOCKEY INFOS
    //////////////////////////////
    public function user_infos()
    {
    	$res = array();
    	$user = $this->auth_mac();
    	
    	if (!is_numeric($user)) {
    		$res['status'] 		= 0;
    		$res['username'] 	= $user->username;
    	}
    	else $res['status'] = $user;
    	
    	echo json_encode($res);
    	
    	$this->make_json();
    }
    
    //////////////////////////
    /////////////// VERIFY KEY
    //////////////////////////
    public function auth_mac()
    {
    	if ( isset($post->mac) and 
    		isset($post->key) and 
    		isset($post->stamp) )
    	{
	    	$user = ORM::factory('user')
	    		->where('mac', $post->mac)
	    		->find();
	    		
	    	$md5 = md5(
	    		$post->mac . 
	    		$post->stamp . 
	    		$this->salt);
	    	
	    	//if (abs ($post->stamp - time()) < 30)
	    	if (true)
	    	{
	    		if ( $post->key == $md5 ) return $user;
	    		else return 1; // Clé incorrecte 
	    	}
	    	else return 2; // Délai dépassé
	    }
	    else return 3; // Données POST incomplètes
    }
    
    ///////////////////////////
    ///////////////// MAKE JSON
    ///////////////////////////
    public function make_json()
    {
    	$this->profiler->disable();
        $this->auto_render = false;
        header('content-type: application/json');
    }
    	
}