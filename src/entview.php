<?php	
	if (isset($_GET['id']))
		include 'entitem.php';
	elseif (isset($_GET['game']) && isset($_GET['type']))
		include 'entlist.php';
	elseif (isset($_GET['game']))
		include 'entcats.php';
	else
		include 'entindex.php';
	
?>