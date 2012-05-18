<h1>Ajouter un lieu</h1>

<?php 

echo form::open_multipart();

echo form::label('ref', 'Référence : ');
echo form::input('ref', $location->ref);

echo "<br /><br />";

echo form::label('ref', 'Classe : ');
echo form::dropdown('classe', Kohana::lang('chocobo.classes'), $location->classe);

echo "<br /><br />";

echo form::label('picture', 'Image : ');
echo form::upload('picture');

foreach($fields as $type => $value)
{
	echo "<br /><br />";
	echo form::label('type[]', 'Champ : ');
	echo form::input('type[]', $type) . ' ';
	echo form::label('value[]', 'Valeur : ');
	echo form::input('value[]', $value);	
}

echo '<span class="location_field">';
echo "<br /><br />";
echo form::label('type[]', 'Champ : ');
echo form::input('type[]', '') . ' ';
echo form::label('value[]', 'Valeur : ');
echo form::input('value[]', '');
echo '</span> ';

echo html::anchor('', '+ ajouter champ', array('id' => 'addFields'));

echo "<br /><br />";

echo form::submit('submit', 'Sauvegarder');

echo form::close();

?>

<script>

$(document).ready(function(){

	$('#addFields').on('click', function(){
		var field = $('.location_field').html();
		$(this).before(field);
		return false;
	});
	
});

</script>