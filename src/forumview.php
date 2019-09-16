<?php
	if (isset($_GET['thread']))
		include 'forumthreadview.php';
	elseif (isset($_GET['id']))
		include 'forumthreadlist.php';
	elseif (isset($_GET['editthread']))
		include 'forumeditthread.php';
	elseif ((isset($_GET['deletepost'])) || (isset($_GET['editpost'])))
		include 'forumeditpost.php';
	elseif (isset($_GET['threadbump']))
		include 'forumbumpthread.php';
	else
		include 'forumlist.php';
?>
