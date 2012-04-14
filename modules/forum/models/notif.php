<?php defined('SYSPATH') or die('No direct script access.');
 
class Notif_Model extends ORM {
    
    protected $belongs_to = array('topic');
    
}