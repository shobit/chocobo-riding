<?php defined('SYSPATH') OR die('No direct access allowed.');

class Init_Controller extends Template_Controller 
{

	// liste tous les messages
	public function type ()
	{
		$topics = ORM::factory('topic')->find_all();
		
		foreach($topics as $topic)
		{
			// gestion des tags
            $tag_ids = array();

			$tag_name = trim($topic->type);
			$tag = ORM::factory('tag', array('ref' => url::title($tag_name)));
			if ( ! $tag->loaded)
			{
				$tag->ref = url::title($tag_name);
				$tag->name = $tag_name;
				$tag->save();
			}
			$tag_ids[] = $tag->id;

            $topic->tags = $tag_ids;
            
            $topic->save();
		}
		
		echo "ok"; exit;
	}
	
}
