<?php

// forum
$config['forum'] = 'topic/index';
$config['forum/page/([0-9]+)'] = "topic/index/all/page/$1";
$config['forum/topic/([0-9]+)'] = "topic/view/$1";
$config['forum/topic/([0-9]+)/page/([0-9]+)'] = "topic/view/$1/page/$2";
$config['forum/comment/edit/([0-9]+)'] = "comment/edit/$1";

$config['forum/([a-z]+)'] = "topic/index/$1";
$config['forum/([a-z]+)/page/([0-9]+)'] = "topic/index/$1/page/$2";

$config['forum/discussion/add'] = "topic/edit/0/discussion";
$config['forum/question/add'] = "topic/edit/0/question";
$config['forum/idea/add'] = "topic/edit/0/idea";
$config['forum/bug/add'] = "topic/edit/0/bug";
$config['forum/reaction/add'] = "topic/edit/0/reaction";

$config['forum/topic/edit/([0-9]+)'] = "topic/edit/$1";
$config['forum/topic/delete/([0-9]+)'] = "topic/delete/$1";
