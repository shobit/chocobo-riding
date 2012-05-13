<?php

// topics
$config['topics'] = 'topic/index';
$config['topics/all/page/([0-9]+)'] = "topic/index/all/page/$1";

$config['topics/search/tags/([a-zA-Z0-9-_]+)'] = "topic/index/$1";
$config['topics/search/tags/([a-zA-Z0-9-_]+)/page/([0-9]+)'] = "topic/index/$1/page/$2";

$config['topics/new'] = "topic/edit";

$config['topics/([0-9]+)'] = "topic/view/$1";
$config['topics/([0-9]+)/page/([0-9]+)'] = "topic/view/$1/page/$2";

$config['topics/([0-9]+)/edit'] = "topic/edit/$1";

$config['topics/delete'] = "topic/delete";

// dscussions
$config['discussions'] = 'discussion/index';
$config['discussions/page/([0-9]+)'] = "discussion/index/page/$1";

$config['discussions/new'] = "discussion/add";

$config['discussions/([0-9]+)'] = "discussion/view/$1";
$config['discussions/([0-9]+)/page/([0-9]+)'] = "discussion/view/$1/page/$2";

// comments
$config['comments/new'] = "comment/add";
$config['comments/([0-9]+)/edit'] = "comment/edit/$1";
$config['comments/([0-9]+)/favorite'] = "comment/favorite/$1";

// http://chocobo-riding.menencia.com/topics					v
// http://chocobo-riding.menencia.com/topics/page/1				v
// http://chocobo-riding.menencia.com/topics/new				v
// http://chocobo-riding.menencia.com/topics/22					v
// http://chocobo-riding.menencia.com/topics/22/page/1			v
// http://chocobo-riding.menencia.com/topics/edit				v
// http://chocobo-riding.menencia.com/topics/delete				
// http://chocobo-riding.menencia.com/topics/lock				
// http://chocobo-riding.menencia.com/topics/archive			

// http://chocobo-riding.menencia.com/discussions				v
// http://chocobo-riding.menencia.com/discussions/page/1		v
// http://chocobo-riding.menencia.com/discussions/new			v
// http://chocobo-riding.menencia.com/discussions/22			v
// http://chocobo-riding.menencia.com/discussions/22/page/1		v
// http://chocobo-riding.menencia.com/discussions/22/edit		
// http://chocobo-riding.menencia.com/discussions/22/delete		
// http://chocobo-riding.menencia.com/discussions/22/archive	

// http://chocobo-riding.menencia.com/comments/new				v
// http://chocobo-riding.menencia.com/comments/22/edit			
// http://chocobo-riding.menencia.com/comments/22/delete		
// http://chocobo-riding.menencia.com/comments/22/favorite		v

// http://chocobo-riding.menencia.com/messages/22/favorite		(later)
