<?php 
	//echo html::script('js/shoutbox.js'); 
?>

<div id="first_lign">
	<?php
		$user = $this->session->get('user');
		if ($user->id >0) {
			$chocobo = $this->session->get('chocobo');
			
			echo html::image('images/menu/hall.gif');
            echo html::anchor('races', Kohana::lang('menu.races')).' | ';

			echo html::image('images/menu/profil.gif');
			echo html::anchor('chocobo/view/'.$chocobo->name, Kohana::lang('menu.profile')).' | ';

			echo html::image('images/theme/gil.gif');
			echo html::anchor('shop', Kohana::lang('menu.shop')).' | ';

			echo html::image('images/menu/sac.gif');
			echo html::anchor('inventory', Kohana::lang('menu.bag')).' | ';

			//echo html::image('images/menu/hammer.gif');
			//echo html::anchor('error', Kohana::lang('menu.forge')).' | ';

			echo html::image('images/menu/fusion.gif');
			echo html::anchor('fusion', Kohana::lang('menu.fusion')).' | ';

			//echo html::image('images/menu/histoire.gif');
			//echo html::anchor('error', Kohana::lang('menu.story')).' | ';

			//echo html::image('images/menu/connectes.gif');
			//echo html::anchor('error', Kohana::lang('menu.clan')).' | ';
			
			echo html::image('images/icons/cel.png');
			echo html::anchor('success/view/'.$user->username, Kohana::lang('menu.successes')).' | ';
			
			echo html::image('images/menu/compte.gif');
			echo html::anchor('user/view/'.$user->username, Kohana::lang('menu.account'));
		}
	?>
</div>
<div id="second_lign">
	<?php
		if ($user->id >0) {
			echo html::anchor('events', Kohana::lang('menu.events')).' | ';
			//echo html::anchor('circuits', Kohana::lang('menu.mappemonde')).' | ';
			echo html::anchor('rankings', Kohana::lang('menu.rankings')).' | ';
			//echo html::anchor('error', Kohana::lang('menu.stats')).' | ';
			echo html::anchor('tutorial', Kohana::lang('menu.tutorial')).' | ';

			echo html::anchor('shoutbox', Kohana::lang('menu.shoutbox'), 
				array('onClick'=>'javascript:openShoutbox(); return false;')).' | ';

			echo html::anchor('topics', Kohana::lang('menu.forum')).' | ';
			echo html::anchor('discussions', Kohana::lang('menu.messages')).' | ';
			//echo html::anchor('error', Kohana::lang('menu.donations')).' | ';
			//echo html::anchor('error', Kohana::lang('menu.credits'));
		} else {
			echo html::image('images/menu/profil.gif');
            echo html::anchor('home', Kohana::lang('menu.presentation')).' | ';

            echo html::image('images/menu/inscription.gif');
            echo html::anchor('register', Kohana::lang('menu.registration')).' | ';

			echo html::image('images/menu/histoire.gif');
			echo html::anchor('error', ucfirst(Kohana::lang('menu.tutorial'))).' | ';

            //echo html::image('theme/etoile.png');
            //e($html->link('shoutbox', 'javascript:void(0);',
			//	array('onClick'=>'javascript:openShoutbox();')).' | ');

            echo html::image('images/menu/connectes.gif');
			echo html::anchor('topics', ucfirst(Kohana::lang('menu.forum'))).' | ';

            echo html::image('images/menu/hall.gif');
			echo html::anchor('error', ucfirst(Kohana::lang('menu.credits')));
		}
	?>
</div>