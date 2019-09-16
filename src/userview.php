<?php
	//control panel
	if (isset($_GET['control']) && isset($_SESSION['uid']))
		include 'usercontrol.php';
	elseif (isset($_GET['journal']) && isset($_SESSION['uid']))
		include 'userjournal.php';
	elseif (isset($_GET['id']) || isset($_GET['name']))
		include 'profile.php';
	elseif (isset($_GET['manage']) && isset($lvl) && ($lvl >= 40))
		include 'useradmin.php';
	else
		include 'userlist.php';
?>