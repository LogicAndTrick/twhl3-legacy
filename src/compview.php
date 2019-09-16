<?php
	if (isset($_GET['results']))
		include 'compwin.php';
	elseif (isset($_GET['hub']))
		include 'comphub.php';
	elseif (isset($_GET['comp']))
		include 'compbrief.php';
	elseif (isset($_GET['new']))
		include 'compcreate.php';
	elseif (isset($_GET['submit']))
		include 'compsubmit.php';
	elseif (isset($_GET['thanks']))
		include 'compthanks.php';
	else
		include 'complist.php';
?>