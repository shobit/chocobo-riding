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
    	
    	// MARKITUP
    	echo html::stylesheet('js/lib/markitup/skins/simple/style.css', 'screen', false);
    	echo html::stylesheet('js/lib/markitup/sets/textile/style.css', 'screen', false);
    	echo html::script('js/lib/markitup/jquery.markitup.js');
    	echo html::script('js/lib/markitup/sets/textile/set.js');
    	
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

<div id="jgrowl_content"><?= new View("elements/jgrowl") ?></div>

<div id="global">
	<div id="global1"></div>
	<div id="global2">

		<div id="before">
			<div id="before1"></div>
			<div id="before2"></div>
			<div id="before3"></div>
			<div class="clearBoth"></div>
		</div>
		
		<div id="site">
			<div id="site1"></div>
			<div id="site2">
		
				<div id="header">
					<div id="header1"></div>
					<div id="header2"></div>
					<div id="header3"></div>
					<div class="clearBoth"></div>
				</div>
				
				<div id="hmenu">
					<div id="hmenu1"></div>
					<div id="hmenu2"><?php echo new View('elements/hmenu'); ?></div>
					<div id="hmenu3"></div>
					<div class="clearBoth"></div>
				</div>
				
				<div id="main">
					<div id="main1"></div>
					<div id="main2">
					
						<div id="content">
							<div id="content1"></div>
							<div id="content2"><?= $content ?></div>
							<div id="content3"></div>
							<div class="clearBoth"></div>
						</div>
						
						<div id="vmenu">
							<div id="vmenu1"></div>
							<div id="vmenu2"><?= new View('elements/vmenu') ?></div>
							<div id="vmenu3"></div>
							<div class="clearBoth"></div>
						</div>
				
						<div class="clearBoth"></div>
				
					</div>
					<div id="main3"></div>
				
				</div>
			
			</div>
			<div id="site3"></div>
			
		</div>
				
		<div id="footer">
			<div id="footer1"></div>
			<div id="footer2"><?php echo new View('elements/footer'); ?></div>
			<div id="footer3"></div>
			<div class="clearBoth"></div>
		</div>
		
		<?php 
		$user = $this->session->get('user');
    	if ($user->id >0) { ?>
		<div id="after">
			<div id="after1"></div>
			<div id="after2"><?php echo new View('elements/after'); ?></div>
			<div id="after3"></div>
			<div class="clearBoth"></div>
		</div>
		<?php } ?>
		
	</div>
	<div id="global3"></div>

</div>

</body>

</html>