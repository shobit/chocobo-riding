<?php defined('SYSPATH') or die('No direct script access.');
 
class arr_Core {
 
	public static function order ( & $array, $key, $arg = 'asc' )
	{
		foreach ($array as $k) 
		{
	        ${'sort_' . $key}[] = $k[$key];
	    }
	    
	    if ($arg == 'asc') $sort = SORT_ASC;
	    if ($arg == 'desc') $sort = SORT_DESC;
	
	    array_multisort(${'sort_' . $key}, $sort, $array);
	}
}
 
?>