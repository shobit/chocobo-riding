<?php defined('SYSPATH') or die('No direct script access.');
 
class Flow_Model extends ORM {
    
    protected $belongs_to = array('user', 'discussion');
    
    /**
	 * marque comme supprimé la discussion
	 * et supprime la discussion si tous les autres flux sont supprimés
	 *
	 */
	public function to_delete()
	{
		$to_delete = TRUE;
		
		foreach ($this->flows as $flow)
		{
			if ($flow->deleted === FALSE)
			{
				$to_delete = FALSE;
				break;
			}
		}
		
		if ($to_delete === TRUE)
		{
			$this->discussion->delete();
		}
	}
    
}