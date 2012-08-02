<h2>Nombre de sujets : <?php echo count($topics) ?></h2>

<?php if ($user->loaded): ?>
	<?php echo html::anchor('topics/new', 'Créer', array('class' => 'button blue fright')) ?>
	<div class="clearright;"></div>
<?php endif; ?>

<table id="topics" class="table1">
	
	<thead>
		<tr>
			<th class="len100">Type</th>
			<th class="lenmax">Titre</th>
			<th class="len100">Dernier post</th>
			<th></th>
			<th class="len100"></th>
		</tr>
	</thead>

	<tbody>
	<?php
	foreach ($topics as $n => $t) 
	{
		$topic = ORM::factory('topic', $t->id);
		$nbr_comments = count($topic->comments);
		$first_comment = $topic->comments[0];
		$last_comment = $topic->comments[$nbr_comments - 1];
		$notified = false;
		
		$notifications = $topic->get_notifications($user->id);
		$nbr_notifications = count($notifications);
		$not_seen = ($nbr_notifications > 0) ? ' not_seen': '';		
	?>
		
	<tr class="topic<?php echo $not_seen ?>" id="topic<?php echo $topic->id ?>">	
		<td class="wrapper-type">
			<?php if ( ! empty($topic->type)): ?>
				<span class="type <?php echo $topic->type ?>"><?php echo $topic->type ?></span>
			<?php endif; ?>
		</td>
			
		<td class="title">
			<span class="nbr_comments"><?php echo ($nbr_comments - 1) ?></span> 
			<?php echo $topic->title ?>
		</td>

		<td class="date">
			<?php echo $topic->updated ?>
		</td>

		<td>
			<?php echo date::display($topic->updated) ?>
		</td>
		
		<td>
			<?php echo html::anchor('topics/' . $topic->id, 'Lire', array('class' => 'button green')) ?>
		</td>
	</tr>
		
	<?php
	}
	?>
	</tbody>

</table>

<script>
$(document).ready(function(){

	$('#topics').dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aaSorting": [ [2,'desc'] ],
		"aoColumnDefs": [ 
            {
                "fnRender": function ( oObj, sVal ) {
                    return oObj.aData[3];
                },
                "bUseRendered": false,
                "aTargets": [ 2 ]
            },
            { "bVisible": false,  "aTargets": [ 3 ] }
        ],
		"oLanguage": {
			"sUrl": "js/lib/dataTables/i18n/dataTables.french.txt"
		}
	});

	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	// Afficher les +1/-1
	$('.topic').hover(function(){
		$(this).find('.nbfavsw').show();
		$(this).find('.favw').show();
		$(this).find('.options').fadeIn('slow');
	}, function(){
		var nbfavsw = $(this).find('.nbfavs');
		if (nbfavsw.text() == '+0') {
			$(this).find('.nbfavsw').hide();
		}
		$(this).find('.favw').hide();
		$(this).find('.options').hide();
	});
	
	// mettre en favori un topic
	$('.fav').click(function(){
		var id = $(this).attr('id').substring(3);
		$('#fav' + id).attr('src', baseUrl + 'images/forum/loading.gif');
		$.ajax({
			type: 'POST',
			url: baseUrl + 'comments/' + id + '/favorite/',
			dataType: 'json',
			success: function(data) {
				var nbfavs = parseInt($('#nbfavs' + id).text());
				nbfavs = (data.icon == 'new') ? nbfavs + 1: nbfavs - 1;
				$('#nbfavs' + id).text('+' + nbfavs);
				
				$('#fav' + id).attr('src', baseUrl + 'images/forum/star-' + data.icon + '.png');
			}
		});
		return false;
	});
		
	$('.delete_topic')
		.click(function(){
			var topic_id = $(this).attr('id').substring(5);
			$.post(baseUrl + 'topics/delete', {'id': topic_id}, function(data){
				if (data.success) {
					$('#topic' + topic_id).slideUp();
				}
			});
			return false;
		});

});
</script>
