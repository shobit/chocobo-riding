<?php
class Fusion_Controller extends Template_Controller {

	// view & flash - mating 2 chocobos
	public function index()
	{
		$this->authorize('logged_in');
		$user = $this->session->get('user');
		$chocobo = $this->session->get('chocobo');
		$gender = ($chocobo->gender == 2) ? 1 : 2;
		
		$form = array (
			'chocobo' => '',
            'partner' => '',
            'nut' => '', 
            'boxes' => ''
        );
        
        $errors = $form;
		
		if ($_POST)
		{
			$post = new Validation($_POST);
			
			$post->add_rules('partner', 'required'); # required
			$post->add_rules('nut', 'required');
			
			$post->add_callbacks('chocobo', array($this, '_chocobo_matches')); # verifyng
			$post->add_callbacks('partner', array($this, '_partner_matches'));
			$post->add_callbacks('nut', 	array($this, '_nut_matches'));
			$post->add_callbacks('boxes', 	array($this, '_no_boxes'));
			
			# executing fusion
			if ($post->validate()) 
			{
				# initing nut
				$nut = ORM::factory('nut', $post->nut);
				$nut->initiate_colors();
				$nut->initiate_jobs();
				
				# setting fusion delay (1 day)
				$delay = time() + 24*3600;
				$male_id = ($chocobo->gender == 2) ? $chocobo->id : $post->partner;
				$female_id = ($chocobo->gender == 1) ? $chocobo->id : $post->partner;
				
				# setting father /male
				$male = ORM::factory('chocobo', $male_id);
				$nut->change_colors($male);
				$nut->change_jobs($male);
				if ($male->user->id == $user->id) $male->mated = $delay;
				$male->nb_mated++;
				$male->save();
				
				# setting mother /female
				$female = ORM::factory('chocobo', $female_id);
				$nut->change_colors($female);
				$nut->change_jobs($female);
				if ($female->user->id == $user->id) $female->mated = $delay;
				$female->nb_mated++;
				$female->save();
				
				# adding baby
				$level = min($male->level, $female->level);
				$baby = ORM::factory('chocobo')->generate($user->id, $nut, $level);
				$baby->father = $male->id;
				$baby->mother = $female->id;
				$baby->save();
				
				# deleting nut
				$nut->delete();
				
				$user->nbr_birthdays++;
				$user->save();
				
				gen::add_jgrowl(Kohana::lang('jgrowl.new_baby'));
				url::redirect('fusion');
			} 
			else 
			{
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('form_error_messages'));
            }
		}
		
		$view = new View('pages/fusion');
		$view->user = $user;
		$view->chocobo = $chocobo;
		$view->gender = $gender;
		
		$partners = ORM::factory('chocobo')
			->where('gender', $gender)
			->where('level', $chocobo->lvl_limit)
			->where('lvl_limit', $chocobo->lvl_limit)
			->where('status', 0)
			->orderby(NULL, 'RAND()')
			->find_all(5);
		
		$view->partners = $partners;
		
		$view->nuts = ORM::factory('nut') # looking for nuts
			->where('user_id', $user->id)
			->find_all();
			
		$view->errors = $errors;
        $view->form = $form;
		$this->template->content = $view;
	}
	
	// include - verify the chocobo matches
	public function _chocobo_matches(Validation $array, $field) {
		$chocobo = $this->session->get('chocobo');
		$res = false;
		if ($chocobo->status != 0) $res = true;
		if ($chocobo->mated > time()) $res = true;
		if ($res) {
			$array->add_error($field, 'matches');
		}
	}
	
	// include - verify the partner matches
	public function _partner_matches(Validation $array, $field) {
		$chocobo = $this->session->get('chocobo');
		$partner = ORM::factory('chocobo')->find($array[$field]);
		$res = false;
		if ($partner->id == 0) $res = true;
		if ($partner->id == $chocobo->id) $res = true;
		if ($partner->gender == $chocobo->gender) $res = true;
		if ($partner->user->id == $chocobo->user->id and $partner->mated > time()) $res = true;
		if ($partner->user->id == $chocobo->user->id and $partner->status != 0) $res = true;
		if ($res) {
			$array->add_error($field, 'matches');
		}
	}
    
    // include - verify the nut exists
	public function _nut_matches(Validation $array, $field) {
        $user = $this->session->get('user');
        $nut_matches = 
        	(bool) ORM::factory('nut')
			->where('id', $array[$field])
			->where('user_id', $user->id)
			->count_all();
        if (!$nut_matches) {
            $array->add_error($field, 'matches');
        }
    }
	
	// include - verify there's a box for the baby chocobo
	public function _no_boxes(Validation $array, $field) {
		$user = $this->session->get('user');
        $box_free = (count($user->chocobos) < $user->boxes);
        if (!$box_free) {
            $array->add_error($field, 'no_boxes');
        }
    }
    	
}
