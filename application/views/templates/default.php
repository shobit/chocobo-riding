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
		echo html::stylesheet('css/' . $design . '/style.css', 'screen', FALSE);
		
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
		
		// TIPSY
		echo html::script('js/lib/tipsy/jquery.tipsy.js'); 
		echo html::stylesheet('js/lib/tipsy/tipsy.css', 'screen', false);

		// FANCYBOX
		echo html::stylesheet('js/lib/fancybox/jquery.fancybox.css', 'screen', false);
		echo html::script('js/lib/fancybox/jquery.fancybox.pack.js');

		// DATATABLE
		echo html::script('js/lib/dataTables/media/js/jquery.dataTables.min.js'); 
		echo html::stylesheet('js/lib/dataTables/media/css/jquery.dataTables.css', 'screen', false);
		
		// JTIP
		echo html::script('js/lib/jtip/jtip.js'); 
		echo html::stylesheet('js/lib/jtip/jtip.css', 'screen', false);
		
		// MYSCRIPT
		echo html::script('js/script.js');
	?>
	<!--[if IE]> 
	<?php echo html::stylesheet('js/lib/bubbletip/bubbletip-IE.css', 'screen', false); ?>
	<![endif]-->

	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Advent+Pro' rel='stylesheet' type='text/css'>
	<link href="http://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet" type="text/css">

	<script type="text/javascript">
	
		var baseUrl = "<?php echo (IN_PRODUCTION ? "http://chocobo-riding.com/" : "http://localhost:8888/chocobo-riding/www/"); ?>";
		
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
	<!--a href="https://github.com/Menencia/chocobo-riding">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub">
	</a-->

	<div id="jgrowl_content"><?php echo View::factory("elements/jgrowl") ?></div>
	
	<div id="header"></div>

	<div class="hmenu">
		<div class="wrapper-hmenu">
			<div class="logo">
				<span class="part1">Chocobo</span><span class="part2">-</span><span class="part3">Riding</span><span class="part4">beta</span>
			</div>
			<div class="search">
				<?php echo View::factory('elements/search') ?>
			</div>
		</div>
	</div>

	<div class="site">
					
		<div class="vmenu">
			<?php echo View::factory('elements/menu') ?>
		</div>
		
		<div id="page">
			<?php echo $content ?>
		</div>
		
		<div class="clearleft"></div>
		
	</div>

	<div id="footer">
		<?php //echo View::factory('elements/footer') ?>
	</div>

</body>

</html>