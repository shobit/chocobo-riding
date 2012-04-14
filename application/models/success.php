<?php defined('SYSPATH') or die('No direct script access.');
 
class Success_Model extends ORM {

	protected $belongs_to = array("title");
    
    public function add_jgrowl()
    {
    	$link = html::anchor('success/view', Kohana::lang('success.'.$this->title->name.'.name'));
        $image = html::image('images/successes/'.$this->title->name.'.jpg', array("class"=>"success_jgrowl"));
        $text = $image.Kohana::lang('jgrowl.success');
        $text = str_replace('%name%', $link, $text);
        $desc = '<small><i>'.substr(Kohana::lang('success.'.$this->title->name.'.desc'), 0, 45).'</i></small>';
        $text = str_replace('%desc%', $desc, $text);
    	
    	gen::add_jgrowl($text, TRUE);
	}

}