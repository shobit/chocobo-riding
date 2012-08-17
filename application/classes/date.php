<?php defined('SYSPATH') or die('No direct script access.');

class Date extends Kohana_Date {

	/**
 	 * Retourne le temps relatif ou absolu restant joliment écrit
 	 *
 	 * @param	int			Nombre de secondes restants
 	 * @return	string		Texte qui indique le temps relatif ou absolu restant
 	 */
	public static function display($date)
	{
		setlocale(LC_TIME, "fr_FR");
		
		$tps = max(1, time() - $date);
		
		if ($tps < 60) 
		{ 
			$ago = 'un instant';
		}
		else 
		{
			$tps = floor($tps / 60);
			
			if ($tps == 1) // minutes
			{
				$ago = $tps . ' minute';
			}
			elseif ($tps < 60) 
			{ 
				$ago = $tps . ' minutes';
			}
			else 
			{
				$tps = floor($tps / 60); 
				
				if ($tps == 1) // hours
				{
					$ago = $tps . ' heure';
				}
				else
				{ 
					$ago = $tps . ' heures'; 
				}
			}
		}
		
		$time = date('Y-m-d');
		list($year, $month, $day) = explode('-', $time);
		$today = mktime(0, 0, 0, $month, $day, $year);
		$yesterday = mktime(0, 0, 0, $month, $day - 1, $year);
		
		if ($today <= $date)
		{
			$short = 'Il y a ' . $ago;
		}
		else if ($yesterday <= $date and $date < $today)
		{
			$short = "Hier à " . strftime("%H:%M", $date);
		}
		else if ($date < $yesterday)
		{
			$short = strftime("%e %b. %Y", $date);
		}
		
		$long = strftime("%e %b. %Y, %H:%M", $date);
		
		return '<span title="' . $long . '">' . $short . '</span>';
	}
	
	/**
	 * Retourne le temps de la course, mode chrono
	 *
	 * @param	float		Temps effectué
	 * @return	string		Texte du temps formaté
	 */
	public static function chrono($time)
	{
		$tps = '';
		$l_part = floor($time);
		$r_part = floor(($time - $l_part)*1000);
		return $l_part . "'<small>" . $r_part . '</small>';
	}

}
