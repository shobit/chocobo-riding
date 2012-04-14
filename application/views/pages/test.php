<h1>Test</h1>

<?php

$user = $this->session->get('user');

$e = ORM::factory('equipment');
$e->generate(10, $user);

echo $e->vignette();

?>