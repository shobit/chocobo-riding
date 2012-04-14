<?php defined('SYSPATH') or die('No direct script access.');
 
class gen_Core {
	
	public static function get_username($id)
	{
		$user = ORM::factory('user', $id);
		return $user->username;
	}
	
	public static function languages() 
	{
		return array(
			'fr_FR' => 'Français',
			'es_ES' => 'Español',
			'en_EN' => 'English'
			//'ar_AR' => 'عربي'
		);
	}
	
	public static function display_date($tps)
	{
		return date('H\:i\:s', $tps);
	}
	
	public static function time_left($date)
	{
		setlocale(LC_TIME, "fr_FR");
		
		$tps = max(1, time() - $date);
		
		if ($tps == 1) // seconds
		{
			$short = $tps." seconde";
		}
		elseif ($tps < 60) 
		{ 
			$short = $tps.' secondes';
		}
		else {
			$tps = floor($tps/60);
			
			if ($tps == 1) // minutes
			{
				$short = $tps." minute";
			}
			elseif ($tps < 60) 
			{ 
				$short = $tps.' minutes';
			}
			else {
				$tps = floor($tps/60); 
				
				if ($tps == 1) // hours
				{
					$short = 'environ '.$tps." heure";
				}
				elseif ($tps < 24)
				{ 
					$short = $tps.' heures'; 
				}
			}
		}
		
		$yesterday_time = ((int) strftime("%e", time())) - 1;
		$yesterday_date = ((int) strftime("%e", $date));
		if ($yesterday_time == $yesterday_date and time()-$date<=60*60*24*3)
		{
			$long = "Hier, ".strftime("%H:%M", $date);
		}
		else
		{
			$long = strftime("%e %B, %H:%M", $date);
		}
		
		if (isset($short))
		{
			$short = 'Il y a '.$short;
		}
		else
		{
			$short = $long;
		}
		
		return array(
			'short'=> utf8_encode( $short ), 
			'long' => utf8_encode( $long )
		);
		
		// $date = gen::time_left();
		// echo html::anchor('#', $date['short'], array('title'=>$date['long']));
	}
	
	public function colour($colour, $format='code') 
	{
		switch ($format) {
    		case 'code':
				$tab = array(
					'yellow', 'red', 'green', 
					'blue', 'black', 'white', 
					'silver', 'gold');
    			$res = $tab[$colour];
    			break;
    		case 'display':
 		   		$tab = array(
 		   			'Jaune', 'Rouge', 'Vert', 
 		   			'Bleu', 'Noir', 'Blanc', 
 		   			'Argent', 'Or');
 		   		$res = $tab[$colour];
    			break;
    	}
    	return $res;
	}
	
	// FUNC:
    // var $content STRING
    // var $expire 	BOOLEAN
    public static function add_jgrowl($content, $expire=FALSE) 
    {
    	$session = new Session;
    	if (!$expire) $jgrowls = $session->get('jgrowls1');
    	else $jgrowls = $session->get('jgrowls2');
    	if (!is_array($jgrowls)) $jgrowls = array();
    	$jgrowls[] = $content;
    	if (!$expire) $session->set_flash('jgrowls1', $jgrowls);
    	else 
    	{
    		$session->set_flash('jgrowls2', $jgrowls);
    		$session->expire('jgrowls2');
    	}
    }
    
// TODO FUNC: Informations du chocobo en popup (HTML)
	public static function vignette($content, $bubble) 
	{
		$uniqid = uniqid();
		$res  = "";
		$res .= html::anchor('', $content, array('id'=>'a'.$uniqid));
		$res .= '<div id="t'.$uniqid.'" style="display:none;">
			<b>'.$content.'</b><br />'.$bubble.'
		</div>
		<script type="text/javascript">
			$(window).bind(\'load\', function() {
				$(\'#a'.$uniqid.'\').bubbletip($(\'#t'.$uniqid.'\'), {
					deltaDirection: \'right\',
					offsetLeft: 20
				});
			});
		</script>';
		
		return $res;
	}
	
}