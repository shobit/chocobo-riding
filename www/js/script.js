/*
 * Chocobo's view
 */
$(document).ready(function() 
{
	// jTIP

	$('a.jtiprel').jTipOn('rel');


	// FANCYBOX

	$('.fancybox').fancybox();


	// CHOCOBO APTS UPDATES

	$('.points a').click(function() {
		var url =  $(this).attr("href").split('/'); // Attention quand on changera
		var max = url.length;
		var value = url[max-1];
		var apt = parseInt($('#'+value).text())+1;
		$('#points span').html('<img src="../../images/theme/loading.gif" />');
		$('.points').hide();
		$.ajax(
		{
		   url: "../../chocobo/aptitude_up/"+value, 
		   type:"GET", 
		   dataType: "json", 
		   success: function(result) 
		   {
				if (result > 0)
				{
					$('#points span').html(result);
					$('#'+value).html(apt);
					$('.points').show();
				} 
				else
				{
					$('#points').hide();
				}
		   }
		});
		return false
	});


	// SUCCESS

	$('.locked').hide();
	$('#minimal_view').hide();
	$('#maximal_view').hide();
	$('#maximal_view').show();
	
	$('#minimal_view').click(function() {
		$('.locked').hide("slow");
		$('#minimal_view').hide();
		$('#maximal_view').show();
		return false;
	});

	$('#maximal_view').click(function() {
		$('.locked').show("slow");
		$('#maximal_view').hide();
		$('#minimal_view').show();
		return false;
	});

});

/*
 * Compte à rebours pour les courses
 */
function decompte(id,tps,termine,showtitle) 
{
	if (tps>=0) {
		if (tps>=60*60*24) {
			jours = Math.floor(tps/(60*60*24));
			jours = "["+jours+"] ";
		} else {jours = "";}
		if (tps>=60*60) {
			heures = Math.floor(tps/(60*60)) % 24;
			if (heures<10) {heures='0'+heures;}
			heures += ":";
		} else {heures = "";}
		if (tps>=60) {
			minutes = Math.floor(tps/60) % 60;
			if (minutes<10) {minutes='0'+minutes;}
			minutes += ":";
		} else {minutes = "00:";}
		if (tps>=0) {
			secondes = tps % 60;
			if (secondes<10) {secondes='0'+secondes;}
		}
		$('#'+id).html(jours+heures+minutes+secondes);
		if (showtitle && !$.browser.msie) $('title').html(jours+heures+minutes+secondes+' | Chocobo Riding {BETA} |::');
		tps--;
		setTimeout(function(){decompte(id,tps,termine,showtitle)}, 1000);
	} else {
		$('#'+id).html(termine);
		if (showtitle && !$.browser.msie) $('title').html(termine+' ::| Chocobo Riding {BETA} |::');
	}
}

/*
 * Ouvrir la Shoutbox dans une petite fenêtre (pop-up externe)
 */
function openShoutbox() {
	window.open(
		baseUrl + 'shoutbox', 
		'_blank', 
		'toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, copyhistory=0, menuBar=0, width=400, height=600'
	);
}

/**
 * Initialisation d'une page composée de sections
 */
function init_nav(home) {
	$('a').click(function(event){
		var hash = $(this).attr('href');
		change(hash);
	});

	var hash = location.hash;
	
	if (hash == '') {
		hash = home;
		location.hash = hash;
	}

	change(hash);
}

/**
 * Changement d'une section
 */
function change(hash){
	hash = hash.substring(2);

	$('.nav a').removeClass('selected');
	$('.nav a[href$=' + hash + ']').addClass('selected');

	$('.section').hide();
	$('#' + hash).slideDown();
}
