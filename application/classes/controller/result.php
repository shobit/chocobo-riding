<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Result extends Controller_Template 
{
	
	/**
	 * Supprime un résultat de course et supprime la course si 
	 * tous les autres participants l'ont fait.
	 *
	 * @param $id int ID du résultat de course
	 */
	public function action_delete()
	{
		$this->authorize('logged_in');

		$id = $this->request->param('id');

		$chocobo = Session::instance()->get('chocobo');
		
		$result = ORM::factory('result', $id);

		$msg = '';
		
		if ($result->loaded() === FALSE)
		{
			$msg = __('Résultat de course non trouvé !');
		}
		
		if (empty($msg))
		{
			$msg = __('Résultat de course supprimé !');

			$result->to_delete();
		}
		
		Jgrowl::add($msg);
		
		$this->request->redirect('races');
	}
	
}
