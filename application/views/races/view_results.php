<style>
	.controls {width: 500px; margin: auto; text-align: center; padding: 3px 0 3px 0;}
	
	.allure {position: absolute; top: 0; left: 0; width: 30px; height: 30px;}
</style>

<h2>Course terminée</h2>

<table class="table1">
	<tr>
		<th class="lenmax">Nom</th>
		<th class="len150">Date</th>
	</tr>
	<tr>
		<td><?php echo $race->circuit->name() ?></td>
		<td>
			<?php echo date::display($race->start) ?>
		</td>
	</tr>
</table>

<h2>Chocobos à l'arrivée</h2>

<table class="table1">
	<tr>
		<th class="lenmax">Nom</th>
		<th class="len150">Temps</th>
		<th class="len150">Vitesse</th>
	</tr>

	<?php 
	arr::order($results, 'position', 'asc');
	foreach ($results as $result): 
	?>
	<tr>
		<td><?php echo $result['name'] ?></td>
		<td>
			<?php echo html::image('images/icons/clock.png', array('class' => 'icon12')) ?> 
			<?php echo date::chrono($result['time']) ?>
		</td>
		<td>
			<?php echo html::image('images/icons/speed.jpg', array('class' => 'icon')) ?>
			<?php echo number_format($result['course_avg'], 3, '.', '') ?> m/s
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<div style="width: 600px; margin: auto; display:none;">
	
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

<h2>Simulation</h2>

<table class="table1">
	<tr>
		<th class="len150">Nom</th>
		<th class="lenmax">Progression</th>
		<th class="len150">Vitesse</th>
	</tr>

	<?php 
	arr::order($results, 'box', 'asc');
	foreach ($results as $result): ?>
	
		<tr id="<?php echo $result['name'] ?>">
			<td><?php echo $result['name'] ?></td> 
			<td style="position: relative;">
				<?php echo html::image('images/race/waiting.png', array('class' => 'allure')) ?>
			</td>
			<td>
				<span class="course"></span>
			</div>
		</tr>
	
	<?php endforeach; ?>
	
</table>

<?php //echo $wave ?>

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
		
		this.left_min = 0;
		this.left_max = 356;
		
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
	
	var script = <?php echo $race->script ?>;
	var length = <?php echo $race->circuit->length ?>;
	var s = new Simulation(script, length);
	s.start();

</script>