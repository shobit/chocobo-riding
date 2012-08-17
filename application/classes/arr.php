<?php defined('SYSPATH') or die('No direct script access.');

class Arr extends Kohana_Arr {

	/**
	 * Ordonne un tableau ($array) selon un des champs ($key) ordonné ou pas
	 * 
	 * @param $array array 
	 * @param $key string 
	 * @param $arg string asc|desc
	 */
	public static function order(&$array, $key, $arg='asc')
	{
		foreach ($array as $k) 
		{
	        ${'sort_'.$key}[] = $k[$key];
	    }
	    
	    if ($arg == 'asc') $sort = SORT_ASC;
	    if ($arg == 'desc') $sort = SORT_DESC;
	
	    array_multisort(${'sort_'.$key}, $sort, $array);
	}

}