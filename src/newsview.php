<?
	if (isset($_GET['post']) && (isset($lvl) && $lvl >= 35))
		include 'newspost.php';
	elseif (isset($_GET['comment']))
		include 'newsmodcomment.php';
	elseif ((isset($_GET['edit']) || isset($_GET['delete'])) && (isset($lvl) && $lvl >= 35))
		include 'newsedit.php';
	elseif (isset($_GET['id']) && ($_GET['id'] != ""))
		include 'newsshow.php';	
	else
		include 'newsarchive.php';
?>