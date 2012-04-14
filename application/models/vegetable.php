<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Nut Model
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Vegetable_Model extends ORM 
{
    
    protected $belongs_to = array('user');
    
    /**
     * Generate a random vegetable.
     * 
     * @access public
     * @param mixed $chance
     * @param mixed $user
     * @return void
     */
    public function generate($chance, $user)
    {
    	// setting name
    	$name = rand(1, 8);
    	
    	// setting interval value
    	$min_value = max(1, $chance - 20);
    	$max_value = 100;
    	
    	// calculating base value
    	$value = rand($min_value, min($chance, $max_value));
    	
    	// setting price
    	$price  = $value;
    	
    	// setting rarity | 0 ~ 4
    	$rarity = floor($chance /23);
    	
    	// creating vegetable
    	$this->user_id 	= $user->id;
    	$this->name 		= $name;
    	$this->rarity		= $rarity;
    	$this->value 		= $value;
    	$this->price 		= $price; 
    	$this->save();
    }
    
    // FUNC: Informations du légume en popup (HTML)
	public function vignette() 
	{
		$res  = " ";
		//$res .= html::image('images/items/vegetables/vegetable'.$this->name.'.gif');
		$res .= html::anchor('void(0);', $this->display_name(), array('id'=>'vegetable'.$this->id.'_a'));
		$res .= '<div id="vegetable'.$this->id.'_t" style="display:none;">
			<b>'.$this->display_name().'</b>
			     <small>';
		
		$apts = array('breath', 'hp', 'mp', 'moral', 'guerison', 'xp', 'rage', 'pc');
		$apt = $apts[$this->name - 1];
		$res .= "<br />".Kohana::lang('chocobo.'.$apt).' +'.$this->value.'%';
		
		$res .=	'</small>
		</div>
		<script type="text/javascript">
			$(\'#vegetable'.$this->id.'_a\').bubbletip($(\'#vegetable'.$this->id.'_t\'), {
				deltaDirection: \'right\',
				offsetLeft: 20
			});
		</script>';
		return $res;
	}
	
	public function display_name()
	{
		switch ($this->name)
		{
			case 1 : $name = "Légume Mimmet"; 	break;
			case 2 : $name = "Légume Krakka";	break;
			case 3 : $name = "Légume Pashana";	break;
			case 4 : $name = "Légume Curiel";	break;
			case 5 : $name = "Légume Guysal";	break;
			case 6 : $name = "Légume Reagan";	break;
			case 7 : $name = "Légume Tantal";	break;
			case 8 : $name = "Légume Sylkis";	break;
		}
		return $name;
	}
	    
}
