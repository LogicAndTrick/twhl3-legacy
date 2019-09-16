<?php
	include 'top.php';
	
	if (isset($_GET['cat']) && $_GET['cat'] != "")
		include 'tutlist.php';
	elseif (isset($_GET['id']) && $_GET['id'] != "")
		include 'tutview.php';
	elseif (isset($_GET['edit']) && $_GET['edit'] != "" && isset($_GET['page']) && $_GET['page'] != "" && ($_GET['page'] > 0))
		include 'tutpageedit.php';
	elseif (isset($_GET['edit']) && $_GET['edit'] != "")
		include 'tutedit.php';
	elseif (isset($_GET['propose']))
		include 'tutprop.php';
	elseif (isset($_GET['viewprops']) && $_GET['viewprops'] != "")
		include 'tutviewproplist.php';
	elseif (isset($_GET['viewprop']) && $_GET['viewprop'] != "")
		include 'tutviewprop.php';
	elseif (isset($_GET['userprop']) && $_GET['userprop'] != "")
		include 'tutuserprop.php';
	elseif (isset($_GET['drafts']))
		include 'tutdraftlist.php';
	elseif (isset($_GET['mytuts']))
		include 'tutuserlist.php';
	elseif (isset($_GET['revision']) && $_GET['revision'] != "")
		include 'tutrevisionview.php';
	elseif (isset($_GET['user']) && $_GET['user'] != "")
		include 'tutlistuser.php';
	elseif (isset($_GET['pagedelete']) && $_GET['pagedelete'] != "")
		include 'tutdeletepageconfirm.php';
	elseif (isset($_GET['newpage']) && $_GET['newpage'] != "")
		include 'tutnewpageconfirm.php';
	elseif (isset($_GET['thanks']))
		include 'tutpropthanks.php';
	else
		include 'tutcatlist.php';
	
	include 'bottom.php';
?>