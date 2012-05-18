<style>
	.locations {width: 100%; margin: 0 0 20px 0;}
	.location {border-bottom: 1px solid #ddd;}
	.location:hover {background-color: #f5f5f5;}
</style>

<h1>Gestion des lieux</h1>

<div class="locations">
<?php foreach ($locations as $location): ?>
	<div class="location">
		<?php
		$id = $location->id;
		echo $id . '. ' . html::anchor('admin/location/edit/' . $id, $location->ref);
		echo ' (' . html::anchor('admin/location/delete/' . $id, 'supprimer') . ')';
		?>
	</div>
<?php endforeach; ?>
</div>

<?php echo html::anchor('admin/location/edit', 'Ajouter un lieu', array('class' => 'button blue')) ?>