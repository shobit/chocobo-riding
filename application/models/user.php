<?php defined('SYSPATH') or die('No direct script access.');
 
class User_Model extends ORM {
    
    protected $has_many = array(
    	'chocobos', 'posts', 'notifs', 'flows', 
    	'vegetables', 'nuts', 'equipment', 'successes');
    	
    protected $has_one = array('design');
    
    protected $has_and_belongs_to_many = array('roles');
    
    public $BOXES_LIMIT = 7;
    public $ITEMS_LIMIT = 20;
    
    /**
     * (void) modifie le montant de Gils du joueur
     *
     * (int) $gils
     */
    public function set_gils ( $gils )
    {
    	$this->gils += $gils;
    	$this->listen_success(array( # SUCCES
			"gils_500",
			"gils_1000",
			"gils_5000",
			"gils_10000"
		));
    }
    
    /**
     * ALLOW
     * if ($user->has_role('admin')) {}
     */
    public function has_role($roles)
    {
    	if ( ! is_array($roles))
    	{
    		return ($this->has(ORM::factory('role', $roles)));
    	}
    	else
    	{
    		foreach($roles as $role)
    		{
    			if ($this->has(ORM::factory('role', $role))) return true;
    		}
    		return false;
    	}
    }
    
    public function role()
    {
    	if ($this->has(ORM::factory('role', 'admin')) ) return "Administrateur";
    	elseif ($this->has(ORM::factory('role', 'modo')) ) return "Modérateur";
    	else return "Jockey";
    }
    
    public function is_connected() 
    {
    	return (time() - $this->connected <= 5*60);
    }
    
    public function display_image($type, $options=null, $url=false) 
    {
		$image = ($this->image == "") ? "default.gif" : $this->image;
		if ($url) return ('upload/users/'.$type.'/'.$image);
		else return html::image('upload/users/'.$type.'/'.$image, $options);
    }
    
	public function display_gender()
	{
		$genders = array(
			'Inconnu', 
			'Masculin',
			'Féminin'
		);
		return $genders[$this->gender];
	}
    
    public function display_status() 
    {
    	return $this->status;
    }
    
    public function display_fame()
    {
    	return number_format($this->fame, 2, '.', '');
    }
    
    public function display_quest()
    {
    	$res = "";
    	if ($this->quest == 0)
    		$res = "Prologue";
    	elseif ($this->quest == 33)
    		$res = "Epilogue";
    	else
    		$res = "Chapitre ".$this->quest;
    	return $res;
    }
    
    public function nbr_items()
    {
    	$nbr = 0;
    	$nbr += count($this->vegetables);
    	$nbr += count($this->nuts);
    	$nbr += ORM::factory('equipment')
    		->where('user_id', $this->id)
    		->where('chocobo_id', NULL)
    		->count_all();
    	return $nbr;
    }
    
    // $user->listen_success("001");
    public function listen_success($refs)
    {
    	if (! is_array($refs)) $refs = array($refs);
	    
	    foreach($refs as $ref)
	    {
	    	$res = false;
	    	switch ($ref)
	    	{
	    		
	    		case "avatar_upload": if ($this->image != "") $res = TRUE; break;
	    		case "gils_500": if ($this->gils > 500) $res = TRUE; break;
	    		case "gils_1000": if ($this->gils > 1000) $res = TRUE; break;
	    		case "gils_5000": if ($this->gils > 5000) $res = TRUE; break;
	    		case "gils_10000": if ($this->gils > 10000) $res = TRUE; break;
	    		case "boxes_3": if ($this->boxes >= 3) $res = TRUE; break;
	    		case "boxes_5": if ($this->boxes >= 5) $res = TRUE; break;
	    		case "boxes_7": if ($this->boxes >= 7) $res = TRUE; break;
	    		case "items_12": if ($this->items >= 12) $res = TRUE; break;
	    		case "items_15": if ($this->items >= 15) $res = TRUE; break;
	    		case "items_20": if ($this->items >= 20) $res = TRUE; break;
	    		case "birthdays_25": if ($this->nbr_birthdays >= 25) $res = TRUE; break;
	    		case "birthdays_50": if ($this->nbr_birthdays >= 50) $res = TRUE; break;
	    		case "birthdays_75": if ($this->nbr_birthdays >= 75) $res = TRUE; break;
	    		case "birthdays_100": if ($this->nbr_birthdays >= 100) $res = TRUE; break;
	    	}
	    	$title = ORM::factory('title')->where('name', $ref)->find();
	    	if ($res and ! $this->success_exists($title->id)) $this->success_add($title->id);
    	}
    }
    
    public function success_exists($title_id)
    {
    	return (bool) ORM::factory('success')
    		->where('user_id', $this->id)
    		->where('title_id', $title_id)
    		->count_all();
    }
    
    public function success_add($title_id)
    {
   		$success = ORM::factory('success');
   		$success->user_id = $this->id;
   		$success->title_id = $title_id;
 		$success->created = time();
  		$success->seen = FALSE;
  		$success->title->nbr_users += 1;
  		$success->title->save();
  		$success->save();
    }
    
    public function delete()
    {
    	foreach ($this->equipment as $equipment) $equipment->delete();
    	foreach ($this->vegetables as $vegetable) $vegetable->delete();
    	foreach ($this->nuts as $nut) $nut->delete();
    	foreach ($this->chocobos as $chocobo) $chocobo->delete();
    	foreach ($this->notifs as $notif) $notif->delete();
    	foreach ($this->flows as $flow) $flow->delete();
    	foreach ($this->successes as $success) $success->delete();
    	$this->db->update(
	   		'posts', 
	   		array('user_id' => null),
	  		array('user_id' => $this->id)
	  	);
	  	$this->db->update(
	   		'waves', 
	   		array('user_id' => null),
	  		array('user_id' => $this->id)
	  	);
	  	$this->design->user_id = null;
    	parent::delete();
    }
 
}
