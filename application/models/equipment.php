<?php defined('SYSPATH') or die('No direct script access.');
 
class Equipment_Model extends ORM {
    
    protected $belongs_to = array('user', 'chocobo');
    
    protected $has_many = array('effects');
    
    /**
     * Générateur aléatoire d'équipement
     * 
     * @access public
     * @param int 		$chance 0~100
     * @param int 		$colour 0~4
     * @param int 		$level 0~100
     * @param object 	$user
     * @return void
     */
    public function generate($chance, $colour, $level, $user)
    {
    	// Effects lists
    	$speeds = array("speed"=>5, "pl_limit"=>100, "pl_up"=>0.1, "pl_recup"=>5);
    	$endurs = array("endur"=>5, "hp_limit"=>50, "hp_up"=>0.1, "hp_recup"=>5);
    	$intels = array("intel"=>5, "mp_limit"=>25, "mp_up"=>0.1, "mp_recup"=>5);
    	$alls   = array("bonus_gils"=>100, "bonus_xp"=>100, "windfall"=>100);
    	
    	// Type | 0 ~ 2
    	$types = array('speed', 'endur', 'intel');
    	$type  = rand(0, 2);
    	
    	// Resistance | 1 ~ 10
    	$resistance = ceil($chance /10);
    	
    	// Element | 0 ~ 4
    	$element = rand(0, 4);
		
		// Rarity | 0 ~ 4
		$rarity = min($level - $chance, $colour);
		$rarity = max($rarity, 0);
		
    	// Nombre d'effets | 1 ~ 3
    	$nbr_effects = floor($rarity /2) +1;
    	$list_effects = array_merge($alls, ${$types[$type].'s'});
    	$rand_effects = array_rand($list_effects, $nbr_effects);
		if ( ! is_array($rand_effects)) $rand_effects[] = $rand_effects;
    	
		foreach ($rand_effects as $effect_name)
    	{
    		$effect 		= ORM::factory('effect');
    		$effect->name 	= $effect_name;
    		$effect->value 	= ceil($chance /100 *$list_effects[$effect_name]);
    		
    		$effects[] 		= $effect;
    	}
    	
    	// Price
    	$price = $chance;
    	
    	// Name
    	$name = $type.'_'.$name;
    	
    	// Finalizing
    	$this->user_id 	= $user->id;
    	$this->chocobo_id 	= null;
    	$this->name 		= $name;
    	$this->rarity		= $rarity;
    	$this->type 		= $type +1;
    	$this->resistance 	= $resistance;
    	$this->element 		= $element;
    	$this->price 		= $price; 
    	$this->save();
    	
    	foreach ($effects as $effect)
    	{
    		$effect->equipment_id = $this->id;
    		$effect->save();
    	}
    }
    
    public function vignette() 
	{
		$res  = " ";
		$res .= html::anchor('void(0);', $this->name(), array('id'=>'equipment'.$this->id.'_a'));
		$res .= '<div id="equipment'.$this->id.'_t" style="display:none;">
			<b>'.$this->name().'</b>
			     <br />Armure : '.$this->resistance;
		foreach($this->effects as $effect)
		{
			$res .= '<br />'.$effect->vignette();
		}
		$res .=	'
		</div>
		<script type="text/javascript">
			$(\'#equipment'.$this->id.'_a\').bubbletip($(\'#equipment'.$this->id.'_t\'), {
				deltaDirection: \'right\',
				offsetLeft: 20
			});
		</script>';
		return $res;
	}
	
	public function name()
	{
		return Kohana::lang('equipment.'.$this->name);
	}
	
	public function delete()
	{
		foreach ($this->effects as $effect) $effect->delete();
		parent::delete();
	}
    
}