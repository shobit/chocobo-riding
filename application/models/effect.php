<?php defined('SYSPATH') or die('No direct script access.');
 
class Effect_Model extends ORM {
    
    protected $belongs_to = array('equipment');
    
    public function vignette()
    {
    	return Kohana::lang('chocobo.'.$this->name).' +'.$this->value;
    }
    
}