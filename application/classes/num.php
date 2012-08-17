<?php defined('SYSPATH') or die('No direct script access.');

class Num extends Kohana_Num {

	/**
 	 * Générer $nbr chiffres aléatoires ayant pour somme $int
 	 *
 	 * @param $int int
 	 * @param $nbr int
 	 */
	public static function split_sum($int, $nbr)
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

	/**
	 * Choisir aléatoirement une clé du tableau selon leurs coefficients
	 *
	 * @param $array array Tableau associatif
	 */
	public static function rand_pick($array)
	{
		if (empty($array)) return 0;

		$max = array_sum($array);
		$alea = rand(1, $max);
		$num = 0;
		
		shuffle($array);
		foreach ($array as $key => $value) 
		{
			if ($value == 0) continue;
			$alea -= $value;
			if ($alea <= 0) 
			{
				$res = $key;
				break;
			}
		}

		return $res;
	}

}
