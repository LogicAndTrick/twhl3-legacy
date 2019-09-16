<?
	include 'middle.php';
	$action = false;
	$commentid = mysql_real_escape_string($_GET['id']);
	
	if ((isset($_GET['id'])) && (isset($lvl)) && $lvl >= 30)
		$action = true;
	
	$getmapq = mysql_query("SELECT * FROM mapcomments WHERE commentID = '$commentid'");
	
	if (($action) && (mysql_num_rows($getmapq) > 0))
	{
		$getmapr = mysql_fetch_array($getmapq);
		$mapid = $getmapr['map'];
		$posterid = $getmapr['poster'];
		$commrating = $getmapr['rating'];
		
		mysql_query("DELETE FROM mapcomments WHERE commentID = '$commentid'");
		mysql_query("UPDATE users SET stat_mvcoms = stat_mvcoms-1 WHERE userID = '$posterid'");
		if ($commrating > 0)
			mysql_query("UPDATE maps SET ratings = ratings-1, rating = rating-$commrating WHERE mapID = '$mapid'");
			
		header("Location: vault.php?map=".$mapid);
	}
	else
	{
		include 'header.php';
		include 'sidebar.php';
		if (!$action)
		{
			$problem = "You are not allowed to do this";
			$back = "vault.php";	
		}
		else
		{
			$problem = "This comment does not exist";
			$back = "vault.php";	
		}
		include 'failure.php';
		include 'bottom.php';
	}
?>