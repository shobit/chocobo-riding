<?php 
$user 	= $this->session->get('user');
$jgrowls1 	= ($this->session->get('jgrowls1')) ? $this->session->get('jgrowls1') : array();
$jgrowls2 	= ($this->session->get('jgrowls2')) ? $this->session->get('jgrowls2') : array();
$jgrowls 	= array_merge($jgrowls1, $jgrowls2);

if (!empty($jgrowls) and 
	($user->id >0 and $user->notif_site) or 
	($user->id==0) ) {
?>

<script>
	$(document).ready(function() 
	{
	<?php foreach ($jgrowls as $content) { ?>
		$.jGrowl(
			'<?php echo addslashes($content) ?>',
			{sticky: true}
		);
	<?php } ?>
	});
</script>

<?php
}  
?>