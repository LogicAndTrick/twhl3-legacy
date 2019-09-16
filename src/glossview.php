<?php	
	if (isset($_GET['id']))
		include 'glossitem.php';
	elseif (isset($_GET['cat']))
		include 'glosslist.php';
	else
		include 'glossindex.php';
	
?>