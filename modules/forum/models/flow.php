<?php defined('SYSPATH') or die('No direct script access.');
 
class Flow_Model extends ORM {
    
    protected $belongs_to = array('user', 'topic');
    
}