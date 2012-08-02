<?php defined('SYSPATH') or die('No direct script access.');
 
class vignette_Core {
	
	public static function display($title, $content, $color='black')
	{
		$rand = uniqid();
		$html = '';
		$html .= html::anchor(
			'', $title, 
			array('class' => 'jtiprel ' . $color, 'rel' => '#vignette' . $rand, 'name' => $title, 'onclick' => 'return false')
		);
		$html .= '<div id="vignette' . $rand . '" style="display:none;">';
		$html .= $content;
		$html .= '</div>';
		
		return $html;
	}
		
}