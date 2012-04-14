<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Wave Model
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Wave_Model extends ORM 
{
    // Relations entre modèles
    protected $belongs_to = array('circuit', 'user');
     
}
