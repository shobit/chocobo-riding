<h1>Test</h1>

<?php

$e = ORM::factory('equipment');
$e->generate(0, 1, 3);

echo $e->vignette();

?>