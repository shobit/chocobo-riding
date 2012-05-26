<h1>Test</h1>

<?php

 $e = ORM::factory('vegetable');
 $e->generate(0, 100, 3);

 echo $e->vignette();

?>