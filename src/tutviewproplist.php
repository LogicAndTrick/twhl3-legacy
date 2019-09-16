<?
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
?>
	<div class="single-center">
		<h1>Tutorial Proposals</h1>
		<h2><a href="tutorial.php">Tutorials</a> > Proposals</h2>
<?
		$result = mysql_query("SELECT propID, propuser, propdifficulty, propname, propdetails, uid FROM tutorialproposals LEFT JOIN users ON propuser = userID WHERE accepted < 1 ORDER BY propdate DESC");
		
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
			$css="";
			while($row = mysql_fetch_array($result))
			{
			
				$diff="hard";
				if ($row['propdifficulty'] == 0)
					$diff = "easy";
				elseif ($row['propdifficulty'] == 1)
					$diff = "medium";
					
				$desc=$row['propdetails'];
				if (strlen($desc)>100) $desc=substr($desc,0,100) . "...";
?>
			<tr>
				<td><a href="tutorial.php?viewprop=<?=$row['propID']?>"><?=$row['propname']?></a></td>
				<td align="left"><?=$desc?></td>
				<td align="left"><a href="user.php?id=<?=$row['propuser']?>"><?=$row['uid']?></a></td>
				<td><img src="images/tut_<?=$diff?>.png" alt="<?=$diff?>" /></td>
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
		<p class="single-center-content-center">
			There are no active proposals currently.
		</p>
<?
		}
		
		$getpage=mysql_real_escape_string($_GET['viewprops']);
		
		$propcount = 15;
		$startat = 0;
		$page = 1;
		
		// get the number of props
		$checkpropq = mysql_query("SELECT count(*) AS cnt FROM tutorialproposals WHERE accepted > 0");
		$checkprops=mysql_fetch_array($checkpropq);
		$numprops = $checkprops['cnt'];
		
		$lastpage = ceil($numprops/$propcount);
		
		// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
		if ($getpage == "last") $page = $lastpage;
		elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
		elseif (($getpage-1)*$propcount > $numprops) $page = 1;
		else $page = $getpage;
		
		$startat = ($page-1)*$propcount;
		
		$url = "tutorial.php?viewprops=";
		
		$result = mysql_query("SELECT propID, propuser, propname, propdetails, uid, accepted FROM tutorialproposals LEFT JOIN users ON propuser = userID WHERE accepted > 0 ORDER BY propdate DESC LIMIT $startat,$propcount");
		
?>
		<span class="page-index">
			<?=makeindex($page,5,$lastpage,$url)?>
		</span>	
		<h2 class="top-border">Past Proposals</h2>
<?
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
			$css="";
			while($row = mysql_fetch_array($result))
			{
				if ($row['accepted'] == 1)
					$ans = "no";
				if ($row['accepted'] == 2)
					$ans = "yes";
					
				$desc=$row['propdetails'];
				if (strlen($desc)>100) $desc=substr($desc,0,100) . "...";
?>
			<tr>
				<td><a href="tutorial.php?viewprop=<?=$row['propID']?>"><?=$row['propname']?></a></td>
				<td align="left"><?=$desc?></td>
				<td align="left"><a href="user.php?id=<?=$row['propuser']?>"><?=$row['uid']?></a></td>
				<td><img src="images/tut<?=$ans?>.png" alt="easy" /></td>
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
		<p class="single-center-content-center">
			There are no past proposals right now.
		</p>
<?
		}
?>
	</div>