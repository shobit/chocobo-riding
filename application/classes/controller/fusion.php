<?php

class Controller_Fusion extends Controller_Template {

	/**
	 * Vue fusion
	 */
	public function action_index()
	{
		$this->authorize('logged_in');
		
		$user = Auth::instance()->get_user();
		$chocobo = Session::instance()->get('chocobo');
		$gender = ($chocobo->gender == 2) ? 1 : 2;

		$partners = ORM::factory('chocobo')
			->join('chocobos')
			->on('chocobo.level', '=', 'chocobos.lvl_limit')
			->where('chocobo.gender', '=', $gender)
			->where('chocobo.race_id', '=', 0)
			->order_by(DB::expr('RAND()'))
			->limit(5)
			->find_all();
		
		$nuts = ORM::factory('nut')
			->where('user_id', '=', $user->id)
			->find_all();

		$post = Validation::factory($_POST)
			->rule('chocobo', array($this, '_chocobo_matches'), array(':validation', ':field'))
			->rule('partner', array($this, '_partner_matches'), array(':validation', ':field'))
			->rule('nut', array($this, '_nut_matches'), array(':validation', ':field'))
			->rule('boxes', array($this, '_no_boxes'), array(':validation', ':field'));

		if ($_POST AND $post->check())
		{
			# initing nut
			$nut = ORM::factory('nut', $post['nut']);
			$nut->initiate_colors();
			$nut->initiate_jobs();
			
			# setting fusion delay (1 day)
			$delay = time() + 24*3600;
			$male_id = ($chocobo->gender == 2) ? $chocobo->id : $post['partner'];
			$female_id = ($chocobo->gender == 1) ? $chocobo->id : $post['partner'];
			
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
			
			Jgrowl::add(__('Un bébé chocobo est né dans votre écurie !'));
			
			$this->request->redirect('fusion');
		}
		else
		{
			$errors = $post->errors();
		}

		$this->template->content = View::factory('pages/fusion')
			->set('user', $user)
			->set('chocobo', $chocobo)
			->set('gender', $gender)
			->set('partners', $partners)
			->set('nuts', $nuts)
			->bind('errors', $errors)
			->bind('values', $_POST);
	}
	
	/**
	 * Vérifie si le chocobo est apte à s'accoupler
	 * 
	 * @param $array object Validation
	 * @param $field sting Champ
	 */
	public function _chocobo_matches($array, $field) 
	{
		$chocobo = Session::instance()->get('chocobo');

		if ($chocobo->race_id != 0) 
		{
			$msg = __("Votre chocobo est sur le départ d'une course.");
		}

		if ($chocobo->mated > time()) 
		{
			$msg = __("Votre chocobo doit attendre avant de pouvoir s'accoupler à nouveau.");
		}

		if (isset($msg)) 
		{
			$array->error($field, $msg);
		}
	}
	
	/**
	 * Vérifie si le partenaire est apte à s'accoupler
	 * 
	 * @param $array object Validation
	 * @param $field sting Champ
	 */
	public function _partner_matches($array, $field) 
	{
		$chocobo = Session::instance()->get('chocobo');
		$partner = ORM::factory('chocobo', $array[$field]);
		
		if ($partner->loaded() === FALSE) 
		{
			$msg = __('Vous devez choisir un partenaire.');
		}

		else if ($partner->id == $chocobo->id) 
		{
			$msg = __('Le partenaire doit être différent de votre chocobo.');
		}

		else if ($partner->gender == $chocobo->gender)
		{
			$msg = __('Le partenaire doit être du genre opposé.');
		}
		
		else if ($partner->user->id == $chocobo->user->id AND $partner->mated > time()) 
		{
			$msg = __("Le partenaire doit attendre avant de pouvoir s'accoupler à nouveau.");
		}
		
		else if ($partner->user->id == $chocobo->user->id AND $partner->race_id != 0) 
		{
			$msg = __("Le partenaire est sur le départ d'une course.");
		}

		if (isset($msg)) 
		{
			$array->error($field, $msg);
		}
	}
    
    /**
	 * Vérifie si la noix est présente
	 * 
	 * @param $array object Validation
	 * @param $field sting Champ
	 */
	public function _nut_matches($array, $field) 
	{
        $user = Auth::instance()->get_user();
        $nut = ORM::factory('nut', $array[$field]);

        if ($nut->loaded() === FALSE) 
		{
			$msg = __('Vous devez choisir une noix.');
		}

		else if ($nut->user_id != $user->id) 
		{
			$msg = __('Cette noix ne vous appartient pas.');
		}

        if (isset($msg))  
        {
            $array->error($field, $msg);
        }
    }
	
	/**
	 * Vérifie si l'écurie est assez grande
	 * 
	 * @param $array object Validation
	 * @param $field sting Champ
	 */
	public function _no_boxes($array, $field) 
	{
		$user = Auth::instance()->get_user();
        $box_free = ($user->chocobos->count_all() < $user->get_boxes());
        
        if ($box_free === FALSE) 
        {
            $array->error($field, __("Votre écurie n'est pas assez grande pour un nouveau chocobo."));
        }
    }
    	
}
