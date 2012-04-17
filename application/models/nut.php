<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Nut Model
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Nut_Model extends ORM 
{

	protected $belongs_to = array('user');
	
    // Relations entre modèles
	
	var $colours = array(); # array[8]
	var $jobs = array();
	
    /**
	 * Nut random generator
	 * 
	 * @access public
	 * @param mixed $chance
	 * @param mixed $user
	 * @return void
	 */
	public function generate($chance, $user)
	{
    	// setting interval value
		$min_value = max(1, $chance - 20);
		$max_value = 100;
		
		// calculating base value
		$value = rand($min_value, min($chance, $max_value));
		
		// setting name
		$name = floor($value / 12.5);
		
		//setting color
		$colour = floor($value / $name);
		
		//setting job
		$job = floor($value / $name);
		
		// setting price
		$price  = $value;
		
    	// setting rarity | 0 ~ 4
		$rarity = floor($value / 23);
		
		$this->user_id 	= $user->id;
		$this->name 		= $name;
		$this->rarity		= $rarity;
		$this->gender 		= rand(1, 2);   
		$this->level 		= 1;
		$this->colour 		= $colour;
		$this->job			= $job;
		$this->price 		= $price; 
		$this->save();
	}
	
    /**
	 * Nut bubbletip
	 * 
	 * @access public
	 * @return void
	 */
	public function vignette() 
	{
		$res  = " ";
		//$res .= html::image('images/items/nuts/nut'.$this->name.'.gif');
		$res .= html::anchor('void(0);', $this->name(), array('id'=>'nut'.$this->id.'_a'));
		$res .= '<div id="nut'.$this->id.'_t" style="display:none;">
			<b>'.$this->name().'</b>
				 ';
		if ($this->level > 0) $res .= "<br />Niveau +".$this->level;
		if ($this->speed > 0) $res .= "<br />Vitesse +".$this->speed;
		if ($this->intel > 0) $res .= "<br />Intelligence +".$this->intel;
		if ($this->endur > 0) $res .= "<br />Endurance +".$this->endur;
		$res .=	'
		</div>
		<script type="text/javascript">
			$(\'#nut'.$this->id.'_a\').bubbletip($(\'#nut'.$this->id.'_t\'), {
				deltaDirection: \'right\',
				offsetLeft: 20
			});
		</script>';
		return $res;
	}
	
	/**
	 * Display nut name
	 * 
	 * @access public
	 * @return void
	 */
	public function name()
	{
		switch ($this->name)
		{
			case 1 : $name = "Noix de Peipo"; 	break;
			case 2 : $name = "Noix de Luchile";	break;
			case 3 : $name = "Noix de Sahara";	break;
			case 4 : $name = "Noix de Lasan";	break;
			case 5 : $name = "Noix de Pram";	break;
			case 6 : $name = "Noix de Porov";	break;
			case 7 : $name = "Noix de Caroube";	break;
			case 8 : $name = "Noix de Zeio";	break;
		}
		return $name;
	}
	
	/**
	 * Init colours
	 * 
	 * @access public
	 * @return void
	 */
	public function initiate_colours()
	{
		switch ($this->name)
		{
			case 1 : $res = array(0, 0, 0, 0, 0, 0, 0, 0); $num = 0; break; // jaune
			case 2 : $res = array(0,10, 0, 0, 0, 0, 0, 0); $num = 1; break; // rouge
			case 3 : $res = array(0, 0,10, 0, 0, 0, 0, 0); $num = 2; break; // bleu
			case 4 : $res = array(0, 0, 0,10, 0, 0, 0, 0); $num = 3; break; // vert
			case 5 : $res = array(0,10, 0,10, 5, 0, 0, 0); $num = 4; break; // noir
			case 6 : $res = array(0, 0,10,10, 0, 5, 0, 0); $num = 5; break; // argent
			case 7 : $res = array(0,10, 0,10, 0, 0, 5, 0); $num = 6; break; // blanc
			case 8 : $res = array(0,10,10,10, 5, 5, 5, 1); $num = 7; break; // or
		}
		if ($this->colour > 0) $res[$num] += $this->colour;
		$this->colours = $res;
	}
	
	/**
	 * Change colours with chocobo stats
	 * 
	 * @access public
	 * @param mixed $chocobo
	 * @return void
	 */
	public function change_colours($chocobo)
	{
		$s = $chocobo->speed;
		$i = $chocobo->intel;
		$e = $chocobo->endur;
		if 		($s>100 and $i>100 and $e>100) 	$num = 7; 
		elseif 	($s>75 and $i>50 and $e>75) 	$num = 6;
		elseif 	($s>50 and $i>75 and $e>75) 	$num = 5;
		elseif 	($s>75 and $i>75 and $e>50) 	$num = 4;
		elseif 	($s>25 and $i>25 and $e>50) 	$num = 3;
		elseif 	($s>25 and $i>50 and $e>25) 	$num = 2;
		elseif 	($s>50 and $i>25 and $e>25) 	$num = 1;
		else 									$num = 0;
		$this->colours[$num] += 5; 
	}
	
	/**
	 * Pick colour for baby chocobo
	 * 
	 * @access public
	 * @return void
	 */
	public function choose_colour()
	{
		$this->colours[0] = max(0, 100 - array_sum($this->colours));
		$alea = rand(1, 100);
		$num = 0;
		$sum = $this->colours[0];
		while ($sum <= $alea or $num == 8)
		{
			$num ++;
			$sum += $this->colours[$num];
		}
		return $num;
	}
	
	public function initiate_jobs()
	{
		$this->jobs = array(11, 10, 8, 7, 5, 4, 2, 1);
	}
	
	public function change_jobs($chocobo)
	{
		$this->jobs[$chocobo->job] += 5;
	}
	
	public function choose_job($level)
	{
		$paliers = array(20, 40, 30, 50, 40, 60, 50, 70);
		$res = 0; $i = 7;
		while ($i >= 0 and $res == 0)
		{
			if ($level >= $paliers[$i])
			{
				$rand = rand(1, 100);
				$chance = $this->colours[$i] +($level -$paliers[$i]) /2;
				if ($rand <= $chance) $res = $i;
			}
			$i --;
		}
		return $res;
	}
	
}
