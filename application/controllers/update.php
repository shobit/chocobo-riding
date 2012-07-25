<?php
class Update_Controller extends Template_Controller {

	/**
	 * Ajouter ou éditer un update
	 */
	public function edit($id)
	{
		$this->template = View::factory('templates/fancybox');
		$this->template->content = View::factory('updates/fb_edit')
			->bind('form', $form);

		$update = ORM::factory('update', $id);
		$form = $update->as_array();
		if ($update->date == '00-00-00 00:00:00') { $form['date'] = 0; }

		$this->profiler->disable();
    }

    public function ajax_edit($id)
    {
    	$update = ORM::factory('update', $id);

    	$post = Validation::factory($_POST);
    	$post->add_rules('type', 'required');
    	$post->add_rules('title', 'required');

    	$success = FALSE;

    	// Suppression
       	if (isset($post->delete) and $post->delete)
       	{
       		$success = TRUE;

       		$update->delete();
       	}

    	if ($post->validate())
    	{
    		$success = TRUE;

    		$update->type = $post->type;
    		$update->title = $post->title;

    		if (isset($post->content)) 
    		{ 
    			$update->content = $post->content; 
    		}
    		
    		$update->date = (!empty($post->date)) ? $post->date : date('Y-m-d H:i:s');
			
			$update->save();
    	}

    	$res['success'] = $success;
		
		$this->auto_render = false;
        $this->profiler->disable();
        header('content-type: application/json');

		echo json_encode($res);
    }
    	
}
