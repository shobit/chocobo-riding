<?php defined('SYSPATH') or die('No direct script access.');
 
class date_Core {
	
	public static function short($tps)
	{
		$time = time();
		if ($time-24*3600 < $tps)
			return date('H\:i', $tps);
		else
			return date('j M', $tps);
	}
	
	
}