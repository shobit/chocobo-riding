<h1><?php echo Kohana::lang('home.title'); ?></h1>
<div id="prelude"><?php echo Kohana::lang('home.prelude'); ?></div>

<div class="leftPart2">
	<p><?php echo Kohana::lang('home.text1'); ?></p>
	<p><?php echo Kohana::lang('home.text2'); ?></p>
	
		
	<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FChocobo-Riding%2F101734573226354&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>
	
</div>

<div class="leftPart">
	<center>
		<p><?php echo html::image('images/theme/home.jpg', array('width'=>200)); ?></p><br />
		<p><?php 
			echo html::anchor('users/new', 
				Kohana::lang('home.register'), 
				array('class'=>'button')
			); 
            ?></p>
	</center>
</div>
	