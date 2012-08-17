<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_User extends Model_Auth_User {
	
	protected $_has_many = array(
		'chocobos' => array(),
		'roles' => array('model' => 'role', 'through' => 'roles_users'),
		'successes' => array(),
		'vegetables' => array(),
		'nuts' => array(),
		'equipment' => array(),
		'message_notifications' => array(),
		'discussion_notifications' => array('model' => 'message_notification'),
		'message_favorites' => array('model' => 'message', 'through' => 'messages_users'),
	);
		
	//protected $_has_one = array('design');
	
	//protected $_has_and_belongs_to_many = array('comments_favorites' => 'c_favorites');
	
	/**
	 * Règles par défaut du modèle supprimées
	 */
	public function rules()
	{
		return array();
	}

	/**
	 * Filtres par défaut du modèle supprimés
	 */
	public function filters()
	{
		return array();
	}

	/**
	 * Actions qui terminent la connexion d'un joueur
	 *
	 * @return void
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Sélectionne les chocobos du joueur
			$chocobos = $this->chocobos->find_all();

			// S'il n'en a pas, en créer un (première connexion)
			if (count($chocobos) == 0)
			{
				// Génération d'une noix de base
				$nut = ORM::factory('nut');
				$nut->gender = rand(1, 2);
				
				$chocobo = ORM::factory('chocobo')->generate($this->id, $nut);
			}
			else
			{
				$chocobo = $chocobos[0];
			}

			Session::instance()->set('chocobo', $chocobo);

			// Actualise la date de la dernière connexion
			$this->connected = time();

			$this->update();
		}
	}

	public static function get_users()
	{
		return ORM::factory('user')
			->where('banned', '=', 0)
			->where('deleted', '=', 0)
			->find_all();
	}

	/**
	 * Ajoute ou retire une somme d'argent au montant de gils du joueur
	 * @param int
	 */
	public function set_gils($gils)
	{
		$this->gils += $gils;
		if ($gils > 0)
		{
			$this->listen_success(array( # SUCCES
				"gils_500",
				"gils_1000",
				"gils_5000",
				"gils_10000"
			));
		}
	}
	
	/**
	 * Retourne vrai si l'utilisateur a le ou les rôles demandés
	 * 
	 * @param $roles string|array 
	 * @return boolean 
	 */
	public function has_role($roles)
	{
		if ( ! is_array($roles))
		{
			return $this->has('roles', $roles);
		}
		else
		{
			foreach ($roles as $role)
			{
				if ($this->has('roles', $role)) 
					return TRUE;
			}
			return false;
		}
	}
	
	/**
	 * Affiche les rôles d'un utilisateur
	 *
	 * @return string
	 */
	public function role()
	{
		if (Auth::instance()->logged_in(array('admin'))) 
		{
			$role = 'Administrateur';
		}
		else if (Auth::instance()->logged_in(array('admin'))) 
		{
			$role = "Modérateur";
		}
		else 
		{
			$role = "Jockey";
		}
		return $role;
	}
	
	public function is_connected() 
	{
		return (time() - $this->connected <= 5*60);
	}
	
	/**
	 * affiche l'image de l'utilisateur
	 *
	 * @param $type         thumbnail ou mini
	 * @param $options      attributs de l'images
	 * @param $url          renvoie une image si FALSE, le lien sinon
	 */
	public function image( $type, $options = NULL, $url = FALSE) 
	{
		$image = ($this->image == "") ? "default.gif" : $this->image;
		if ($url) return ('upload/users/' . $type . '/' . $image);
		else return html::image('upload/users/' . $type . '/' . $image, $options);
	}
	
	/*
	 * génère le lien pour accéder au profil du joueur
	 */
	public function link ()
	{
		return html::anchor('users/' . $this->id, $this->username);
	}

	/**
	 * Retourne le nombre de box de l'écurie du joueur
	 * return int
	 */
	public function get_boxes()
	{
		return 2 + $this->boxes;
	}

	/**
	 * Retourne la limite de l'inventaire du joueur
	 * return int
	 */
	public function get_items()
	{
		return 10 + $this->items * 2;
	}

	/**
	 * Retourne le niveau de la boutique du joueur
	 * return int
	 */
	public function get_shop()
	{
		return 1 + $this->shop;
	}

	/**
	 * Retourne le prix du prochain niveau de l'écurie
	 * @return int
	 */
	public function get_boxes_cost()
	{
		return ($this->boxes * $this->boxes + 1) * 1000;
	}

	/**
	 * Retourne le prix du prochain niveau de l'inventaire
	 * @return int
	 */
	public function get_inventory_cost()
	{
		return ($this->items * $this->items + 1) * 1000;
	}

	/**
	 * Retourne le prix du prochain niveau de la boutique
	 * @return int
	 */
	public function get_shop_cost()
	{
		return ($this->shop * $this->shop + 1) * 1000;
	}

	/**
     * Récupère le nombre de notifications d'une discussion pour le joueur
     * 
     * @param $discussion_id int ID du joueur
     * @return int 
     */
	public function get_notifications($discussion_id)
	{
		return $this->discussion_notifications
			->where('discussion_id', '=', $discussion_id)
			->count_all();
	}
	
	public function display_gender()
	{
		$genders = array(
			'Inconnu', 
			'Masculin',
			'Féminin'
		);
		return $genders[$this->gender];
	}
	
	public function display_status() 
	{
		return $this->status;
	}
	
	public function display_fame()
	{
		return number_format($this->fame, 2, '.', '');
	}
	
	public function display_quest()
	{
		$res = "";
		if ($this->quest == 0)
			$res = "Prologue";
		elseif ($this->quest == 33)
			$res = "Epilogue";
		else
			$res = "Chapitre ".$this->quest;
		return $res;
	}
	
	/**
	 * Retourne le nombre d'objets dans l'inventaire du joueur
	 *
	 * @return int
	 */
	public function nbr_items()
	{
		$nbr = 0;
		
		$nbr += $this->vegetables->count_all();
		$nbr += $this->nuts->count_all();
		$nbr += $this->equipment->where('chocobo_id', '=', 0)->count_all();
		
		return $nbr;
	}
	
	// $user->listen_success("001");
	public function listen_success($refs)
	{
		if (! is_array($refs)) $refs = array($refs);
		
		foreach($refs as $ref)
		{
			$res = false;
			switch ($ref)
			{
				
				case "avatar_upload": if ($this->image != "") $res = TRUE; break;
				case "gils_500": if ($this->gils > 500) $res = TRUE; break;
				case "gils_1000": if ($this->gils > 1000) $res = TRUE; break;
				case "gils_5000": if ($this->gils > 5000) $res = TRUE; break;
				case "gils_10000": if ($this->gils > 10000) $res = TRUE; break;
				case "boxes_3": if ($this->get_boxes() >= 3) $res = TRUE; break;
				case "boxes_5": if ($this->get_boxes() >= 5) $res = TRUE; break;
				case "boxes_7": if ($this->get_boxes() >= 7) $res = TRUE; break;
				case "items_12": if ($this->get_items() >= 12) $res = TRUE; break;
				case "items_15": if ($this->get_items() >= 15) $res = TRUE; break;
				case "items_20": if ($this->get_items() >= 20) $res = TRUE; break;
				case "birthdays_25": if ($this->nbr_birthdays >= 25) $res = TRUE; break;
				case "birthdays_50": if ($this->nbr_birthdays >= 50) $res = TRUE; break;
				case "birthdays_75": if ($this->nbr_birthdays >= 75) $res = TRUE; break;
				case "birthdays_100": if ($this->nbr_birthdays >= 100) $res = TRUE; break;
			}
			$title = ORM::factory('title')->where('name', '=', $ref)->find();
			if ($res and ! $this->success_exists($title->id)) $this->success_add($title->id);
		}
	}
	
	public function success_exists($title_id)
	{
		return (bool) ORM::factory('success')
			->where('user_id', '=', $this->id)
			->where('title_id', '=', $title_id)
			->count_all();
	}
	
	public function success_add($title_id)
	{
		$success = ORM::factory('success');
		$success->user_id = $this->id;
		$success->title_id = $title_id;
		$success->created = time();
		$success->seen = FALSE;
		$success->title->nbr_users += 1;
		$success->title->save();
		$success->save();
	}
	
	/**
	 * retourne le nombre de joueurs actifs : actifs, non bannis et non supprimés
	 *
	 */
	public static function get_nbr_players ()
	{
		return ORM::factory('user')
			->where('activated >', 0)
			->where('banned', 0)
			->where('deleted', 0)
			->count_all();
	}

	/**
	 * Ajoute un joueur
	 *
	 * @param $post array Données $_POST
	 */
	public function register($post)
	{
		$this->username 	= $post['username'];
		$this->password 	= sha1($post['password']);
		$this->locale 		= 'fr_FR'; 	// TODO
		$this->design_id	= 1; 		// TODO
		$this->version 		= TRUE;
		$this->created 		= time();
		$this->updated 		= time();

		// Envoi d'un email de vérification si email renseigné
		if ( ! empty($post['email']))
		{
			$this->email 	= $post['email'];

			$to      	= $this->email;
			$from    	= 'mail@menencia.com';
			$subject 	= Kohana::lang('user.register.mail_title');
			$password 	= sha1($this->password);
			$link 		= url::site('user/activate/'.$password);
			$message 	= str_replace(
				array('%username%', '%password%', '%link%'),
				array($this->username, $this->password, $link),
				Kohana::lang('user.register.mail_content')
			);

			email::send($to, $from, $subject, $message, TRUE);
		}
		
		$this->create();
	}
	
	/**
	 * Augmente une des aptitudes du joueur
	 * 
	 * @param $apt string (boxes|inventory|shop)
	 * @return string Message de retour 
	 */
	public function boost($apt)
	{
		$gils = $this->gils;

		switch ($apt) {

			case 'boxes':
				$cost = $this->get_boxes_cost();

				if ($gils < $cost)
				{
					$msg = __("Vous n'avez pas assez d'argent.");
				}

				if ($this->boxes >= 5)
				{
					$msg = __('Vous avez atteint le niveau maximal.');
				}

				if ( ! isset($msg)) 
				{
					$msg = __('Box+ acheté !');
					$this->set_gils(-$cost);
					$this->boxes++;
					$this->update();
					
					$this->listen_success(array( # SUCCES
						"boxes_3",
						"boxes_5",
						"boxes_7"
					));
				}
				break;

			case 'inventory':
				$cost = $this->get_inventory_cost();

				if ($gils < $cost)
				{
					$msg = __("Vous n'avez pas assez d'argent.");
				}

				if ($this->items >= 5)
				{
					$msg = __('Vous avez atteint le niveau maximal.');
				}

				if ( ! isset($msg)) 
				{
					$msg = __('Inventaire+ acheté !');
					$this->set_gils(-$cost);
					$this->items++;
					$this->save();

					$this->listen_success(array( # SUCCES
						"items_12",
						"items_15",
						"items_20"
					));
				}
				break;

			case 'shop':
				$cost = $this->get_shop_cost();

				if ($gils < $cost)
				{
					$msg = __("Vous n'avez pas assez d'argent.");
				}

				if ($this->shop >= 5)
				{
					$msg = __('Vous avez atteint le niveau maximal.');
				}

				if ( ! isset($msg)) 
				{
					$msg = __('Boutique+ acheté !');
					$this->set_gils(-$cost);
					$this->shop++;
					$this->save();
				}
				break;
			
			default:
				$msg = __("Cette aptitude n'existe pas.");
				break;

		}

		return $msg;
	}
	
	/**
	 * marque comme supprimé le joueur
	 * supprime les sujets favoris, les notifications commentaires & messages,
	 * les rôles, les historiques de course, l'avatar
	 *
	 */
	public function to_delete()
	{
		foreach ($this->chocobos as $chocobo) 
		{
			foreach ($chocobo->results as $result)
			{
				$result->to_delete();
			}
		}
		
		$this->db->delete('comments_favorites', array('user_id' => $this->id));
		
		$this->db->delete('comment_notifications', array('user_id' => $this->id));
		
		foreach ($this->flows as $flow) $flow->to_delete();
		
		$this->db->delete('message_notifications', array('user_id' => $this->id));
		
		$this->db->delete('roles_users', array('user_id' => $this->id));
		
		if ($this->image != '') 
		{
			if (is_link('upload/users/mini/' . $this->image)) unlink('upload/users/mini/' . $this->image);
			if (is_link('upload/users/thumbmail/' . $this->image)) unlink('upload/users/thumbmail/' . $this->image);
		}
		
		$this->deleted = time();
		$this->save();
	}
	
	/**
	 * supprime le joueur
	 * supprime le reste des infos du joueur
	 *
	 */
	public function delete ()
	{
		foreach ($this->chocobos as $chocobo) $chocobo->delete();
		
		foreach ($this->equipment as $equipment) $equipment->delete();
		
		foreach ($this->nuts as $nut) $nut->delete();
		
		foreach ($this->successes as $success) $success->delete();
		
		foreach ($this->vegetables as $vegetable) $vegetable->delete();
		
		$delete_user = ORM::factory('deleted_user');
		$delete_user->old_id = $this->id;
		$delete_user->name = $this->username;
		$delete_user->email = $this->email;
		$delete_user->created = time();
		$delete_user->save();
		
		parent::delete();
	}
 
}
