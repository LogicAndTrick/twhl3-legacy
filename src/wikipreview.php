<?php

include 'functions.php';
include 'logins.php';
include 'funcwiki.php';

$content_text = urldecode($_POST['cont']);

echo wiki_format($content_text);
?>