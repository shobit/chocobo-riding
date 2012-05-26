<?php defined('SYSPATH') or die('No direct script access.');
 
class num extends num_Core {
 
	public static function split_sum ($int, $nbr)
	{
		for($i = 0; $i < $nbr - 1; $i++)
		{
			$coeff = rand(1, $int - $nbr + $i + 1);
			$res[] = $coeff;
			$int -= $coeff;
		}
		$res[] = $int;
		shuffle($res);
		return $res;
	}
}
 
?>