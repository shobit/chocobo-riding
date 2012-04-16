<style>
	.race {width: 598px; border: 1px solid #999; padding: 10px 0 10px 0; text-align: center; line-height: 26px;}
	.race .classe {float: left; width: 48px; color: #bbb; font-size: 26px;}
	.race .name {float: left; width: 110px; font-weight: bold;}
	.race .private {float: left; width: 110px;}
	.race .length {float: left; width: 110px;}
	.race .round {float: left; width: 110px;}
	.race .scheduled {float: left; width: 110px;}
	
	.results {width: 500px; border-left: 1px solid #999; border-right: 1px solid #999; margin: auto; display: none;}
	.results .box {border-bottom: 1px solid #999; text-align: center; line-height: 26px; padding: 3px 0 3px 0;}
	.results .position {float: left; width: 100px; color: #bbb; font-size: 26px;}
	.results .chocobo {float: left; width: 100px; text-align: left;}
	.results .chrono {float: left; width: 150px;}
	.results .course_avg {float: left; width: 150px;}
	
	.controls {width: 500px; margin: auto; text-align: center; padding: 3px 0 3px 0;}
	
	.simulation {
		background: url('<?php echo url::base(); ?>images/race/circuit.jpg') no-repeat;
		width: 730px;
		height: 475px;
		margin: 5px 0 0 0;
	}
	.simulation .box {position: relative; height: 50px; padding: 5px 0 5px;}
	.simulation .label {
		position: absolute; 
		width: 82px; 
		top: 24px; 
		left: 5px;
		font-variant: small-caps;
		font-weight: bold;
		font-size: 16px;
		text-align: right;
	}
	.simulation .allure {position: absolute; left:100px; width: 50px; height: 50px;}
	.simulation .event {
		position: absolute; 
		top: 15px; 
		left: 30px; 
		display: none; 
		font-size: 10px; 
		background-color: #fafafa; 
		border: 1px solid #999;
		padding: 3px;
		-moz-box-shadow: 2px 2px 1px #888;
		-webkit-box-shadow: 2px 2px 1px #888;
		box-shadow: 2px 2px 1px #888;
	}
	.simulation .course {color: #999;}
	.simulation .title {font-weight: bold;}
	
	#timer {}
	#slowmotion {font-style: italic; display: none;}
	
	.icon {margin-bottom: -3px;}
	.icon12 {cursor: pointer;}
	.clear {clear: both;}
</style>

<h1><?php echo Kohana::lang('circuit.view_results.title') . ' ' . html::image("images/classes/" . $circuit->classe . ".gif"); ?></h1>
<div id="prelude"><?php echo Kohana::lang('location.' . $circuit->location->code . '.prelude'); ?></div>

<?php echo new View("circuits/description", array('circuit' => $circuit)); ?>

<div style="width: 600px; margin: auto;">
	<div class="race">
		<div class="classe">
			<?php echo $circuit->classe ?>
		</div>
		<div class="name">
			<?php echo $circuit->location->name ?>
		</div>
		<div class="private">
			<?php echo (($circuit->owner !== 0) ? 'Course privée': 'Course officielle') ?>
		</div>
		<div class="length">
			<?php echo $circuit->length ?>m
		</div>
		<div class="round">
			Round <?php //echo $circuit->round; ?>
		</div>
		<div class="scheduled">
			<?php echo date::display($circuit->start) ?>
		</div>
		<div class="clearBoth"></div>
	</div>
	
	<div class="results">
		<?php 
		arr::order($results, 'position', 'asc');
		foreach ($results as $result): ?>
		
			<div class="box">
				<div class="position">
					<?php echo $result['position'] ?>
				</div>
				<div class="chocobo">
					<?php echo $result['name'] ?>
				</div>
				<div class="chrono">
					<?php echo html::image('images/icons/clock.png', array('class' => 'icon12')) ?> 
					<?php echo date::chrono($result['time']) ?>
				</div>
				<div class="course_avg">
					<?php echo html::image('images/icons/speed.jpg', array('class' => 'icon')) ?>
					<?php echo number_format($result['course_avg'], 3, '.', '') ?> m/s
				</div>
				<div class="clearBoth"></div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
	<div class="controls">
		<?php
		echo html::image('images/race/begin.png', array('class' => 'icon12 begin', 'title' => 'Aller au début', 'rel' => 'tipsy')) . ' | ';
		echo html::image('images/race/pause.png', array('class' => 'icon12 pause', 'title' => 'Mettre en pause', 'rel' => 'tipsy')) . ' | ';
		echo html::image('images/race/end.png', array('class' => 'icon12 end', 'title' => 'Aller à la fin', 'rel' => 'tipsy')) . '<br />';
		echo html::image('images/icons/clock.png', array('class' => 'icon12'));
		echo ' <span id="timer"></span>';
		echo ' <span id="slowmotion">ralenti</span>';
		?>
	</div>
</div>

<div class="simulation">
		
	<div style="height: 48px;"></div>
	<?php 
	arr::order($results, 'box', 'asc');
	foreach ($results as $result): ?>
	
		<div class="box">
			<span class="label"><?php echo $result['name'] ?></span> 
			<div id="<?php echo $result['name'] ?>">
				<?php echo html::image('images/race/waiting.png', array('class' => 'allure')) ?>
				<span class="event">
					<span class="distance"></span>
					<span class="course"></span>
					<span class="title"></span>
				</span>
			</div>
		</div>
	
	<?php endforeach; ?>
	
</div>

<?php echo $wave; ?>

<script>

	$(function(){
	
		$('*[rel=tipsy]').tipsy({gravity: 's'});
		
		$('.begin').click(function(){
			s.begin();
		});
		$('.pause').live('click', function(){
			s.pause();
			$(this).toggleClass('play pause');
			$(this).attr('src', baseUrl + 'images/race/play.png');
		});
		$('.play').live('click', function(){
			s.play();
			$(this).toggleClass('play pause');
			$(this).attr('src', baseUrl + 'images/race/pause.png');
		});
		$('.end').click(function(){
			s.end();
		});
	
	});
		
	var Simulation = function (script, length) {
		
		this.tour = 0;
		
		this.script = script;
		this.tours = script.tours;
		
		this.length = length;
		
		this.left_min = 100;
		this.left_max = 488;
		
		this.maintimer = null;
		this.timer = 1000;
		
		this.paused = false;
		
		this.start = function () {
			this.maintimer = setTimeout(function(){
				$('#timer').text('3');
				s.maintimer = setTimeout(function(){
					$('#timer').text('2');
					s.maintimer = setTimeout(function(){
						$('#timer').text('1');
						s.maintimer = setTimeout(function(){
							$('#timer').text('Partez !!!');
							s.maintimer = setTimeout(function(){
								$('#timer').text('00:00');
								$('.allure').attr('src', baseUrl + 'images/race/normal.png');
								s.nextTour();
							}, s.timer);
						}, s.timer);
					}, s.timer);
				}, s.timer);
			}, this.timer);
		};
		
		this.nextTour = function () {
			this.maintimer = setTimeout(function(){
				var points = s.tours[s.tour].points;
				for (var j in points) {
					var left = s.left_min + Math.floor(points[j].distance * s.left_max / s.length);
					$('#' + points[j].chocobo + ' .allure').attr('src', baseUrl + 'images/race/' + points[j].allure + '.png');
					$('#' + points[j].chocobo + ' .allure').css('left', left);
					$('.event').show();
					$('#' + points[j].chocobo + ' .event').css('left', 50 + left);
					$('#' + points[j].chocobo + ' .distance').text(Math.floor(points[j].distance) + 'm').show();
					$('#' + points[j].chocobo + ' .course').text(points[j].course + ' m/s').show();
					$('#' + points[j].chocobo + ' .title').hide();
				
				}
				if (s.tours[s.tour].events.length > 0) {
					s.nextEvent(0);
				} else {
					s.tour++;
					$('#timer').text(s.format(s.tour));
					if (s.tour < s.tours.length) {
						s.nextTour();
					} else {
						s.finish();
					}
				}
			}, this.timer);
		};
		
		this.nextEvent = function (nbr) {
			$('#slowmotion').fadeIn();
			this.maintimer = setTimeout(function(){
				var event = s.tours[s.tour].events[nbr];
				
				$('#' + event.chocobo + ' .distance').hide();
				$('#' + event.chocobo + ' .course').hide();
				$('#' + event.chocobo + ' .title').text(event.title).show();
				$('#' + event.chocobo + ' .allure').attr('src', baseUrl + 'images/race/happy.png');
				
				nbr++;
				if (s.tours[s.tour].events[nbr] !== undefined) {
					$('#slowmotion').fadeOut();
					s.nextEvent(nbr);
				} else {
					s.tour++;
					$('#timer').text(s.format(s.tour));
					if (s.tour < s.tours.length) {
						$('#slowmotion').fadeOut();
						s.nextTour();
					} else {
						$('#slowmotion').fadeOut();
						s.finish();
					}				
				}
			}, this.timer);
		};
		
		this.moveChocobos = function () {
			var points = s.tours[s.tour].points;
			for (var j in points) {
				var left = s.left_min + Math.floor(points[j].distance * s.left_max / s.length);
				$('#' + points[j].chocobo + ' .allure').attr('src', baseUrl + 'images/race/' + points[j].allure + '.png');
				$('#' + points[j].chocobo + ' .allure').css('left', left);
				$('.event').hide();
			}
		},
		
		this.format = function (nbr) {
			var minutes = '00'
			var secondes;
			
			if (nbr < 10) {
				secondes = '0' + nbr;
			} else if (nbr < 60) {
				secondes = nbr;
			} else {
				minutes = Math.floor(nbr / 60);
				secondes = nbr - minutes * 60;
				if (secondes < 10) {
					secondes = '0' + secondes;
				}
			}
			
			return minutes + ':' + secondes;
		};
		
		this.finish = function () {
			setTimeout(function(){
				var tps = $('#timer').text();
				$('#timer').text('FIN (' + tps + ')');
				s.paused = true;
				$('.pause').attr('src', baseUrl + 'images/race/play.png');
				$('.pause').toggleClass('play pause');
				$('.results').slideDown();
			}, this.timer);
		};
		
		this.begin = function () {
			clearTimeout(this.maintimer);
			this.tour = 0;
			this.moveChocobos();
			if ( ! this.paused) {
				this.nextTour();
			}
		};
		
		this.play = function () {
			this.paused = false;
			if (this.tour == this.tours.length - 1) {
				this.tour = 0;
			}
			this.moveChocobos();
			this.nextTour();
		};
		
		this.pause = function () {
			clearTimeout(this.maintimer);
			this.paused = true;
			if (this.tour > 0) { 
				this.tour--;
			}
			this.moveChocobos();
		};
		
		this.end = function () {
			clearTimeout(this.maintimer);
			this.paused = true;
			$('.pause').attr('src', baseUrl + 'images/race/play.png');
			$('.pause').toggleClass('play pause');
			this.tour = this.tours.length - 1;
			this.moveChocobos();
			$('.results').slideDown();
		};
		
	};
	
	var script = <?php echo $circuit->script ?>;
	var length = <?php echo $circuit->length ?>;
	var s = new Simulation(script, length);
	s.start();

</script>