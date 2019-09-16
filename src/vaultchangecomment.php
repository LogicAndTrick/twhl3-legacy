<?
	include 'middle.php';
	$action = false;
	if (isset($_GET['id']) && (isset($usr)))
	{
		$commentid = mysql_real_escape_string($_GET['id']);
		$getmapq = mysql_query("SELECT * FROM mapcomments WHERE commentID = '$commentid'");
		if (mysql_num_rows($getmapq) > 0)
		{
			$getmapr = mysql_fetch_array($getmapq);
			$mapid = $getmapr['map'];
			$posterid = $getmapr['poster'];
			$commrating = $getmapr['rating'];
			$getlastq = mysql_query("SELECT * FROM mapcomments WHERE map = '$mapid' ORDER BY commentID DESC LIMIT 1");
			if (mysql_num_rows($getlastq) > 0)
			{
				$getlastr = mysql_fetch_array($getlastq);
				$lastid = $getlastr['commentID'];
				$lastuser = $getlastr['poster'];
				if ((($commentid == $lastid) && ($usr == $lastuser)) || ((isset($lvl)) && $lvl >= 30))
				{
					$action = true;
				}
			}
		}
	}
	
	if ($action)
	{
		$rating=0;
		if (isset($_POST['rating']) && $_POST['rating']!="")
		{
			$rating=$_POST['rating'];
			if ($rating > 5 || $rating < 1 || !is_numeric($rating)) $rating = 0;
		}
		$text=htmlfilter($_POST['comment'],true);
		
		$addrating = '';
		$addratings = '';
		if ($commrating > 0 && $rating > 0)
		{
			if ($commrating > $rating)
				$addrating = '- '.($commrating - $rating);
			elseif ($commrating < $rating)
				$addrating = '+ '.($rating - $commrating);
		}
		elseif ($commrating > 0 && $rating == 0)
		{
			$addrating = '- '.$commrating;
			$addratings = '- 1';
		}
		elseif ($commrating == 0 && $rating > 0)
		{
			$addrating = '+ '.$rating;
			$addratings = '+ 1';
		}
		
		mysql_query("UPDATE mapcomments SET commtext = '$text', rating = '$rating' WHERE commentID = '$commentid'");
		mysql_query("UPDATE maps SET ratings = ratings $addratings, rating = rating $addrating WHERE mapID = '$mapid'");
		header("Location: vault.php?map=".$mapid);
	}
	else
	{
		include 'header.php';
		include 'sidebar.php';
		$problem = "You cannot edit this comment. It either doesn't exist, isn't yours, or is not the last comment.";
		$back = "vault.php";	
		include 'failure.php';
		include 'bottom.php';
	}
?>