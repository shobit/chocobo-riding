<?php defined('SYSPATH') or die('No direct script access.');
 
class Vignette {
	
	/**
	 * Affiche un pop-up contenant $content au survol du contenu $title
	 * 
	 * @param $title string Titre du pop-up
	 * @param $content string Contenu du pop-up
	 * @param $color string Couleur du titre
	 */
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