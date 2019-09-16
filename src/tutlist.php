<?
	$thecat=mysql_real_escape_string($_GET['cat']);
	$getcatq = mysql_query("SELECT * FROM tutorialcats WHERE sectionID = '$thecat'");
	$allowed = true;
	if (mysql_num_rows($getcatq) > 0)
	{
		$getcatr = mysql_fetch_array($getcatq);
		$catname = $getcatr['name'];
		$catlevel = $getcatr['level'];
		if ($catlevel != 0 && !(isset($lvl) && $lvl >= $catlevel)) $allowed = false;
	}
	else $allowed = false;
	
	$getdiff=mysql_real_escape_string($_GET['diff']);
	$difffilter = '';
	if (!is_numeric($getdiff) || $getdiff < 0 || $getdiff > 2) 
	{
		$getdiff = 'all';
		$difffilter = 'difficulty >= 0';
	}
	else $difffilter = "difficulty = '$getdiff'";
	
	$getorder = mysql_real_escape_string($_GET['order']);
	$orderfilter = '';
	if ($getorder != 'name' && $getorder != 'date' && $getorder != 'author' && $getorder != 'difficulty')
	{
		$getorder = 'name';
		$orderfilter = 'name';
	}
	else $orderfilter = $getorder;
	if ($getorder == 'author') $orderfilter = 'uid';
	
	$ascdesc = mysql_real_escape_string($_GET['dir']);
	$ascdir = '';
	if ($ascdesc != 'asc' && $ascdesc != 'desc')
	{
		$ascdesc = 'asc';
	}
	$ascdir = strtoupper($ascdesc);
	
	if ($allowed)
	{
?>
	<div class="single-center">
		<h1><?=$catname?> Tutorials</h1>
		<h2><a href="tutorial.php">Tutorials</a> > <a href="tutorial.php?cat=<?=$thecat?>"><?=$catname?></a> > [ <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=0&amp;order=<?=$getorder?>&amp;dir=<?=$ascdesc?>">Beginner</a> | <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=1&amp;order=<?=$getorder?>&amp;dir=<?=$ascdesc?>">Intermediate</a> | <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=2&amp;order=<?=$getorder?>&amp;dir=<?=$ascdesc?>">Advanced</a> ] > Order [ <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=<?=$getdiff?>&amp;order=name&amp;dir=<?=($getorder=='name' && $ascdesc=='asc')?'desc':'asc'?>">Name</a> | <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=<?=$getdiff?>&amp;order=date&amp;dir=<?=($getorder=='date' && $ascdesc=='asc')?'desc':'asc'?>">Date</a> | <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=<?=$getdiff?>&amp;order=difficulty&amp;dir=<?=($getorder=='difficulty' && $ascdesc=='asc')?'desc':'asc'?>">Difficulty</a> | <a href="tutorial.php?cat=<?=$thecat?>&amp;diff=<?=$getdiff?>&amp;order=author&amp;dir=<?=($getorder=='author' && $ascdesc=='asc')?'desc':'asc'?>">Author</a> ]</h2>	
		<table class="tutorial-index">
			<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Author</th>
				<th></th>
			</tr>
<?

	$result = mysql_query("SELECT tutorialID, difficulty, authorid, name, description, uid FROM tutorials LEFT JOIN users ON authorid = userID WHERE waiting = 0 AND catID = '$thecat' AND $difffilter ORDER BY $orderfilter $ascdir");
	while($row = mysql_fetch_array($result))
	{
		$diff="hard";
		if ($row['difficulty'] == 0)
			$diff = "easy";
		elseif ($row['difficulty'] == 1)
			$diff = "medium";
		?>
			<tr>
				<td><a href="tutorial.php?id=<?=$row['tutorialID']?>"><?=$row['name']?></a></td>
				<td align="left"><?=$row['description']?></td>
				<td align="left"><a href="user.php?id=<?=$row['authorid']?>"><?=$row['uid']?></a></td>
				<td><img src="images/tut_<?=$diff?>.png" alt="easy" /></td>
			</tr>
		<?
	}
?>
		</table>		
	</div>
<?
	}
	else fail("Category not found","tutorial.php");
?>