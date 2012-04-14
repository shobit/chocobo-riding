<h1>Administration</h1>

<h2>Attribuer une clé API</h2>

<?php
echo form::open('admin/api');
echo form::input('username');
echo form::submit('submit', '>');
echo form::close();
?>