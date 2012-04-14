<h1>Evènements</h1>
<div id="prelude">Bienvenue à vous sur Chocobo Riding! Cette page liste tous les événements importants qui se sont passés récemment. Vous pouvez les commentez et passer directement sur le forum.</div>

<div class="leftPart2">
<?php

$colours = array('red', 'green', 'blue');
$first_topic = $topics[0];
$nbr = count($topics);
foreach ($topics as $n => $topic)
{
	$first_comment = $topic->comments[0];
	?>
	<div class="event<?php if ($topic->id == $first_topic->id) echo " e_first e_".$colours[($nbr-$n)%3] ?>">
		<div class="e_header">
			<div class="e_comments">
				<?= 
				html::anchor(
					'topic/view/'.$topic->id, 
					(count($topic->comments)-1).' commentaires'
				) 
				?>
			</div>
			<div class="e_title"><?= $topic->title ?></div> 
			<div class="e_created">
				Posté: <?php $tl = gen::time_left($first_comment->created); echo $tl['short']; ?>
			</div>
		</div>
		<div class="e_content">
			<?php 
			$textile = new Textile;
			$content = $textile->TextileThis($first_comment->content);
        	echo $content;
        	?>
        </div>
	</div>
	<?php
}

?>
</div>

<div class="leftPart">

	<!-- IMAGE -->
	<center>
		<p><?= html::image('images/theme/mog1.png') ?></p><br />
	</center>

</div>

<div class="clearBoth"></div>
