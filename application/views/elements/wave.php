<script>
$(document).ready(function() 
{
	var id = $('#wave .form input[name=id]').val(); 

	$('#wave .form').submit(function() 
    {
    	$('.loading').html('<img src="'+baseUrl+'images/theme/loading.gif" />');
    	var content = $('#wave .form input[name=content]').val(); 
    	$.post(baseUrl+"wave/add/"+id, { 'content':content }, function() { 
            $('#wave .form input[name=content]').val("");
            refreshWave(); 
        });
        return false;
    });
   
   	var refreshWave = function() {
		$('.loading').html('<img src="'+baseUrl+'images/theme/loading.gif" />'); 
		$.ajax({ 
		    url: baseUrl+"wave/view/"+id, 
		    type: "GET", 
				dataType: "html", 
		    success: function(result){ 
		    	if (result != "") $('#wave .wave_content').html(result);
		    	$('#wave .loading').html(""); 
		    } 
		});
		setTimeout(refreshWave, 60000); 
	};
    
    refreshWave();
});
</script>

<div id="wave">

	<div class="title">Discussion</div>
	
		<div class="corpse">
		
		<div class="form">
		<?php
			echo form::open('wave/add/'.$id);
			echo form::label('content', 'Message: ');
			echo form::hidden('id', $id);
			echo form::input('content', '');
			?> <span class="loading"></span><?php
			//echo form::submit('submit', 'Envoyer');
			echo form::close(); ?>
		</div>
		
		<div class="wave_content"></div>
	
	</div>

</div>
