<?php defined('SYSPATH') or die('No direct script access.');

class Jgrowl {

    /**
     * Ajouter un pop-up jgrowl
     * 
     * @param $content string Contenu du pop-up
     */
    public static function add($content) 
    {
    	$session = Session::instance();

        // On récupère le tableau des jgrowl dans la session
        $jgrowls = $session->get('jgrowls', array());
        
        // On rajoute le nouveau jgrowl
        $jgrowls[] = $content;
    	
        // On met les jgrowl mis à jour dans la session
        $session->set('jgrowls', $jgrowls);
    }

    public static function get_all()
    {
        return Session::instance()->get_once('jgrowls', array());
    }

}
