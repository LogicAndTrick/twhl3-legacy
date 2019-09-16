<?
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
?>
<div class="single-center">
<?
		$getuser = mysql_real_escape_string($_GET['userprop']);
		$prpq = mysql_query("SELECT * FROM users WHERE userID = '$getuser'");
		if (mysql_num_rows($prpq) > 0)
		{
			$prpr = mysql_fetch_array($prpq);
			
			$getpage=mysql_real_escape_string($_GET['page']);
		
			$propcount = 15;
			$startat = 0;
			$page = 1;
			
			// get the number of props
			$checkpropq = mysql_query("SELECT count(*) AS cnt FROM tutorialproposals WHERE propuser = '$getuser'");
			$checkprops=mysql_fetch_array($checkpropq);
			$numprops = $checkprops['cnt'];
			
			$lastpage = ceil($numprops/$propcount);
			
			// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
			if ($getpage == "last") $page = $lastpage;
			elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
			elseif (($getpage-1)*$propcount > $numprops) $page = 1;
			else $page = $getpage;
			
			$startat = ($page-1)*$propcount;
			
			$url = "tutorial.php?userprop=$getuser&amp;page=";
?>
	<h1><?=$prpr['uid']?>'s Proposals</h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2><a href="tutorial.php">Tutorials</a> &gt; <a href="tutorial.php?viewprops=1">Proposals</a> &gt; <?=$prpr['uid']?>'s Proposals</h2>	
<?
			$result = mysql_query("SELECT propID, propuser, propdifficulty, propname, propdetails, uid, accepted FROM tutorialproposals LEFT JOIN users ON propuser = userID WHERE propuser = '$getuser' ORDER BY accepted ASC, propdate DESC LIMIT $startat,$propcount");
			if (mysql_num_rows($result) > 0)
			{
?>	
	<table class="tutorial-index">
		<tr>
			<th>Title</th>
			<th>Extract</th>
			<th>Author</th>
			<th></th>
		</tr>
<?
		while($row = mysql_fetch_array($result))
		{
			if ($row['accepted'] == 0)
			{
				$diff="_hard";
				if ($row['propdifficulty'] == 0)
					$diff = "_easy";
				elseif ($row['propdifficulty'] == 1)
					$diff = "_medium";
			}
			elseif ($row['accepted'] == 2) $diff = "yes";
			elseif ($row['accepted'] == 1) $diff = "no";
				
			$desc=$row['propdetails'];
			if (strlen($desc)>100) $desc=substr($ptext,0,100) . "...";
?>
		<tr>
			<td><a href="tutorial.php?viewprop=<?=$row['propID']?>"><?=$row['propname']?></a></td>
			<td align="left"><?=$desc?></td>
			<td align="left"><a href="user.php?id=<?=$row['propuser']?>"><?=$row['uid']?></a></td>
			<td><img src="images/tut<?=$diff?>.png" alt="easy" /></td>
		</tr>
<?
		}
?>
	</table>
<?
			}
			else
			{
?>
	<p class="single-center-content">
		This user has no Tutorial Proposals.
	<p>
<?
			}
		}
		else
		{
?>
	<h1>User Proposals</h1>
	<p class="single-center-content">
		This user does not exist.
	<p>
<?
		}
?>
</div>