<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Wave Controller - Discussion autour d'une course
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Wave_Controller extends Controller_Template 
{
	
	public function view($id)
	{
		// Droit d'accès
		$this->authorize('logged_in');
		
		// Traitement Ajax ou pas
		if (!request::is_ajax()) 
		{
			url::redirect("races/" . $id);
		}
		else
		{
			$waves = ORM::factory('wave')
				->where('race_id', $id)
				->orderby('created', 'desc')
				->find_all();
			
			if (count($waves) > 0)
			{
				echo '<table class="wave_table">';
				foreach ($waves as $wave) 
				{
					$online = ($wave->user->is_connected()) ? html::image('images/theme/online.png') : "";
					echo "<tr>
						<td>".$online."</td>
						<td>".$wave->user->image('mini')."</td>
						<td><small>(".gen::display_date($wave->created).")</small></td>
						<td><b>".$wave->user->username."</b>: </td>
						<td>".$wave->content."</td>
					</tr>";
				}
				echo "</table>";
			}
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}
	
	public function add($id)
	{
		// User en session
		$user = $this->session->get('user');
		
		// Droit d'accès
		$this->authorize('logged_in');
		
		// Recherche de l'interest
		$post = new Validation($_POST);
    	$post->pre_filter('trim', TRUE);
		$post->add_rules('content', 'required');
		
		$race 	= ORM::factory('race', $id);
		$wave 		= ORM::factory('wave');
			
		if ($race and $post->validate())
		{
			$wave->race_id 	= $id;
			$wave->user_id 	= $user->id;
			$wave->content 		= $post->content;
			$wave->save();
		}
		
		// Traitement Ajax ou pas
		if (!request::is_ajax()) 
		{
			url::redirect("races/" . $id); // TODO
		}
		else
		{
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}
	
}
