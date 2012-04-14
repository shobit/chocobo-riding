<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Wave Controller - Discussion autour d'un circuit
 *
 * @author     Menencia
 * @copyright  (c) 2010
 */
class Wave_Controller extends Template_Controller 
{
	
	public function view($id)
	{
		// Droit d'accès
		$this->authorize('logged_in');
		
		// Traitement Ajax ou pas
		if (!request::is_ajax()) 
		{
			url::redirect("circuit/view/".$id); // TODO
		}
		else
		{
			$waves = ORM::factory('wave')
				->where('circuit_id', $id)
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
						<td>".$wave->user->display_image('mini')."</td>
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
		
		$circuit 	= ORM::factory('circuit')->find($id);
		$wave 		= ORM::factory('wave');
			
		if ($circuit and $post->validate())
		{
			$wave->circuit_id 	= $id;
			$wave->user_id 	= $user->id;
			$wave->content 		= $post->content;
			$wave->save();
		}
		
		// Traitement Ajax ou pas
		if (!request::is_ajax()) 
		{
			url::redirect("circuit/view/".$id); // TODO
		}
		else
		{
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}
	
}
