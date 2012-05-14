<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<title>::| Chocobo Riding {BETA} |::</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php 
		// META
		echo html::meta(array(
			'author' => 'Menencia',
			'description' => "L'Univers des Courses de Chocobos",
			'keywords' => 'chocobo, chocobos, race, races, rides, riding, menencia',
			'generator' => 'Kohana 2.3.4', 
			'robots' => 'index, nofollow'
		));
		
		// FAVICON
		echo html::link('images/theme/favicon.ico', 'icon', 'image/ico');
    	
    	// CSS
    	$design = $this->session->get('design');
    	echo html::stylesheet('css/'.$design.'/style.css', 'screen', false);
    	
    	// RSS
    	echo html::link('topic/rss_updates', 'alternate', 'application/rss+xml', false);
    	
    	// JQUERY
    	echo html::script('js/lib/jquery.js');
    	//echo html::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
    	
    	// FCBKcomplete
    	echo html::stylesheet('js/lib/FCBKcomplete/style.css', 'screen', false);
    	echo html::script('js/lib/FCBKcomplete/jquery.fcbkcomplete.js');
    	
    	// JGROWL
    	echo html::script('js/lib/jGrowl/jquery.jgrowl.min.js');
		echo html::stylesheet('js/lib/jGrowl/jquery.jgrowl', 'screen', false);
    	
		// BUBBLETIP
		echo html::script('js/lib/bubbletip.min.js');
		echo html::stylesheet('js/lib/bubbletip/bubbletip.css', 'screen', false);
		
		echo html::script('js/lib/tipsy/jquery.tipsy.js'); 
		echo html::stylesheet('js/lib/tipsy/tipsy.css', 'screen', false);
		
		// MYSCRIPT
    	echo html::script('js/script.js');
    ?>
	<!--[if IE]> 
	<?php echo html::stylesheet('js/lib/bubbletip/bubbletip-IE.css', 'screen', false); ?>
	<![endif]-->
	
	<script type="text/javascript">
	
		var baseUrl = "<?php echo (IN_PRODUCTION ? "http://chocobo-riding.com/" : "http://localhost/chocobo-riding/www/"); ?>";
		
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-5385370-6']);
		_gaq.push(['_trackPageview']);
		
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		
	</script>
	
</head>

<body>
	<div id="jgrowl_content"><?php echo View::factory("elements/jgrowl") ?></div>

	<div class="container">
	
		<div class="before"></div>
		
		<div class="site">
						
			<div class="header"></div>
				
			<div class="hmenu"></div>
				
			<div class="vmenu">
				<?php echo View::factory('elements/menu') ?>
			</div>
			
			<div class="content">
				<?php echo $content ?>
			</div>
			
			<div class="clearleft"></div>
			
			<div id="footer"><?php echo View::factory('elements/footer') ?></div>
			
		</div>
		
		<div class="after"></div>
	
	</div>
	
</body>

</html>