<?
	include 'middle.php';
	$action = false;
	$mapid = mysql_real_escape_string($_GET['id']);
	
	$mapq = mysql_query("SELECT * FROM maps WHERE mapID = '$mapid'");
	
	if ((isset($_GET['id'])) && (isset($usr)) && (mysql_num_rows($mapq) > 0))
	{
		$mapr = mysql_fetch_array($mapq);
		if (($mapr['owner'] == $usr) || ($lvl > 30)) $action = true;
	}
	
	
	if ($action)
	{
		$mapowner = $mapr['owner'];
		
		// user maps -1
		mysql_query("UPDATE users SET stat_maps = stat_maps+1 WHERE userID = '$mapowner'");
		
		//delete files
		if (is_file("mapvault/".$mapid.".zip")) unlink("mapvault/".$mapid.".zip");
		if (is_file("mapvault/".$mapid.".rar")) unlink("mapvault/".$mapid.".rar");
		if (is_file("mapvault/".$mapid.".jpg")) unlink("mapvault/".$mapid.".jpg");
		if (is_file("mapvault/".$mapid."_small.jpg")) unlink("mapvault/".$mapid."_small.jpg");
		
		//delete map
		mysql_query("DELETE FROM maps WHERE mapID = '$mapid'");
		
		//delete comment uploads
		$comq = mysql_query("SELECT * FROM mapcomments WHERE map = '$mapid' AND attachment = '1'");
		if (mysql_num_rows($comq) > 0) {
			while ($comr = mysql_fetch_array($comq)) {
				if (is_file("mapvault/".$comm['commentID']."_c.zip")) unlink("mapvault/".$comm['commentID']."_c.zip");
				if (is_file("mapvault/".$comm['commentID']."_c.rar")) unlink("mapvault/".$comm['commentID']."_c.rar");
			}
		}

		//delete comments
		mysql_query("DELETE FROM mapcomments WHERE map = '$mapid'");
			
		header("Location: vault.php");
	}
	else
	{
		include 'header.php';
		include 'sidebar.php';
		$problem = "This map does not exist, or you don't have permission to delete it.";
		$back = "vault.php";	
		include 'failure.php';
		include 'bottom.php';
	}
?>