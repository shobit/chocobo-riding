<?php defined('SYSPATH') or die('No direct script access.');
 
class vignette_Core {
	
	public static function display($title, $content)
	{
		$rand = uniqid();
		$html = '';
		$html .= html::anchor(
			'', '<font style="font-weight:bold; color:#000;">' . $title . '</font>', 
			array('class' => 'jtiprel', 'rel' => '#vignette' . $rand, 'onclick' => 'return false')
		);
		$html .= '<div id="vignette' . $rand . '" style="display:none;">';
		$html .= '<font style="font-weight:bold; color:#000;">' . $title . '</font>';
		$html .= '<br /><small>';
		$html .= $content;
		$html .= '</small></div>';
		
		return $html;
	}
		
}