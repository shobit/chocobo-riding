<?php defined('SYSPATH') or die('No direct script access.');
 
class Progress {
	
	/**
	 * Afficher une barre de progression
	 * 
	 * @param $stat_curr int Valeur courante
	 * @param $stat_max int Valeur maximum
	 * @param $width_max int Largeur maximum de la barre
	 */
	public static function display($stat_curr, $stat_max, $width_max)
	{
		$stat = floor($stat_curr / $stat_max *100);
		$color = 'p-green'; 
		if ($stat < 80) { $color = 'p-yellow'; }
		if ($stat < 20) { $color = 'p-red'; }
		$color = 'p-grey';
		$width_curr = floor($stat / 100 * $width_max);
		$html = '<div class="progress ' . $color . '" style="width:' . $width_curr . 'px;"></div>';
		return $html;
	}
		
}