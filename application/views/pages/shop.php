<?php
$gils = $user->gils;
$boxes = $user->boxes;
?>

<h1><?= Kohana::lang('shop.title') ?></h1>
<div id="prelude"><?= Kohana::lang('shop.prelude') ?></div>

<div class="leftPart2">
	
	<table id="shop">
		<thead>
				<tr>
					<th></th>
					<th>Nom</th>
					<th>Prix</th>
					<th></th>
				</tr>
		</thead>
		<tbody>
			<?php foreach($vegetables as $vegetable): ?>
			<tr class="item">
				<?php $price = $vegetable->price ?>
				<td class="icon"><?php echo html::image('images/items/vegetables/vegetable' . $vegetable->name . '.gif') ?></td>
				<td class="name"><?php echo $vegetable->vignette() ?></td>
				<td class="price"><?php echo $price ?> Gils</td>
				<td class="form"> 
					<?php
					if ($gils >= $price) 
					{
						echo html::anchor('vegetable/buy/' . $vegetable->id, Kohana::lang('shop.buy'), array('class' => 'button'));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</div>

<div class="leftPart">
	<center>
	
		<?php echo html::image('images/pages/shop.jpg', array('class' => 'location')) ?>

		<p><?php echo Kohana::lang('shop.gils_remain') ?><br />
			<b><span id="gils"><?php echo $user->gils ?></span></b></p>
	
	</center>
</div>

<script>
$(function(){
	$('#shop').dataTable({
            "oLanguage": {
			    "sProcessing":     "Traitement en cours...",
			    "sLengthMenu":     "Afficher _MENU_ éléments",
			    "sZeroRecords":    "Aucun élément à afficher",
			    "sInfo":           "Affichage de l'élement _START_ à _END_ sur _TOTAL_ éléments",
			    "sInfoEmpty":      "Affichage de l'élement 0 à 0 sur 0 éléments",
			    "sInfoFiltered":   "(filtré de _MAX_ éléments au total)",
			    "sInfoPostFix":    "",
			    "sSearch":         "Rechercher :",
			    "sLoadingRecords": "Téléchargement...",
			    "sUrl":            "",
			    "oPaginate": {
			        "sFirst":    "Premier",
			        "sPrevious": "Précédent",
			        "sNext":     "Suivant",
			        "sLast":     "Dernier"
			    }
			}
        });
});
</script>
