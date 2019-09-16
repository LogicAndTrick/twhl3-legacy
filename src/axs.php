<?php

	$levels=$_SESSION['lvl'];
	$mbr=axslvl($levels);
	if (isset($_SESSION['uid']))
	echo '<a href="user.php?control=1">' . $_SESSION['uid'] . '</a>, level ' . $levels . ': ' . $mbr;// . ' <a href="progress.php">See Progress!</a>';
	else
	//echo '<a href="progress.php">See Progress!</a>';

?>
