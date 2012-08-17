<?php 
$jgrowls = Jgrowl::get_all();

if ( ! empty($jgrowls)):
?>

<script>
	$(document).ready(function() 
	{
		<?php foreach ($jgrowls as $content): ?>
			$.jGrowl(
				'<?php echo addslashes($content) ?>',
				{sticky: true}
			);
		<?php endforeach; ?>
	});
</script>

<?php endif; ?>