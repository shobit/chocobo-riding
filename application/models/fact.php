<?php defined('SYSPATH') or die('No direct script access.');
 
class Fact_Model extends ORM {
    
    protected $belongs_to = array('result');
    
    public function image()
    {
    	$image = "";
    	switch ($this->action)
    	{
    		case "acceleration":
    			$image = "icons/event.png";
    			break;
    		case "rage":
    			$image = "icons/rage.png";
    			break;
    		case "max_speed":
    			$image = "icons/event.png";
    			break;
    		case "level":
    			$image = "icons/exp.png";
    			break;
    		case "classe":
    			$image = "icons/event.png";
    			break;
    		case "speed":
    			$image = "icons/speed.png";
    			break;
    		case "endur":
    			$image = "icons/endur.png";
    			break;
    		case "intel":
    			$image = "icons/intel.png";
    			break;
    		case "gils_total":
    			$image = "icons/gils.png";
    			break;
    		case "xp_total":
    			$image = "icons/exp.png";
    			break;
    		case "reward1":
    			$image = "icons/coffre.png";
    			break;
    		case "reward2":
    			$image = "icons/coffre.png";
    			break;
    		case "reward3":
    			$image = "icons/coffre.png";
    			break;
    	}
    	return html::image("images/".$image);
    }
    
    public function display()
    {
    	$res = "";
    	$chocobo = $this->result->chocobo->name;
    	switch ($this->action)
    	{
    		case 'acceleration':
    			$res = Kohana::lang('fact.acceleration');
    			$speed = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $speed), $res);
    			break;
    		case 'rage':
    			$res = Kohana::lang('fact.rage');
    			$speed = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $speed), $res);
    			break;
    		case 'max_speed':
    			$res = Kohana::lang('fact.max_speed');
    			$speed = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $speed), $res);
    			break;
    		case 'level':
    			$res = Kohana::lang('fact.level');
    			$values = explode('/', $this->values);
    			$nb_levels = $values[0];
    			$level = $values[1];
    			$res = str_replace(
    				array('*chocobo*', '*nb_levels*', '*level*'), 
    				array($chocobo, $nb_levels, $level), $res);
    			break;
    		case 'classe':
    			$res = Kohana::lang('fact.classe');
    			$values = explode('/', $this->values);
    			$classe = $values[1];
    			$res = str_replace(array('*chocobo*', '*classe*'), array($chocobo, $classe), $res);
    			break;
    		case 'speed':
    			$res = Kohana::lang('fact.speed');
    			$apt = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $apt), $res);
    			break;
    		case 'endur':
    			$res = Kohana::lang('fact.endur');
    			$apt = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $apt), $res);
    			break;
    		case 'intel':
    			$res = Kohana::lang('fact.intel');
    			$apt = $this->values;
    			$res = str_replace(array('*chocobo*', '*speed*'), array($chocobo, $apt), $res);
    			break;
    		case 'gils_total':
    			$res = Kohana::lang('fact.gils_total');
    			$apt = $this->values;
    			$res = str_replace(array('*chocobo*', '*gils*'), array($chocobo, $apt), $res);
    			break;
    		case 'xp_total':
    			$res = Kohana::lang('fact.xp_total');
    			$apt = $this->values;
    			$res = str_replace(array('*chocobo*', '*xp_total*'), array($chocobo, $apt), $res);
    			break;
    		case 'reward1':
    			$res = Kohana::lang('fact.reward1');
    			$values = explode('/', $this->values);
    			$item = ORM::factory($values[0])->find($values[1]);
    			$itema = ($item->id >0) ? $item->vignette() : "???";
    			$res = str_replace(array('*chocobo*', '*item*'), array($chocobo, $itema), $res);
    			break;
    		case 'reward2':
    			$res = Kohana::lang('fact.reward2');
    			$values = explode('/', $this->values);
    			$item = ORM::factory($values[0])->find($values[1]);
    			$itema = ($item->id >0) ? $item->vignette() : "???";
    			$res = str_replace(array('*chocobo*', '*item*'), array($chocobo, $itema), $res);
    			break;
    		case 'reward3':
    			$res = Kohana::lang('fact.reward3');
    			$values = explode('/', $this->values);
    			$item = ORM::factory($values[0])->find($values[1]);
    			$itema = ($item->id >0) ? $item->vignette() : "???";
    			$res = str_replace(array('*chocobo*', '*item*'), array($chocobo, $itema), $res);
    			break;
    	}
    	return $res;
    }
    
}