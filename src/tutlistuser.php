<?
	$getuser=mysql_real_escape_string($_GET['user']);
	$userq = mysql_query("SELECT * FROM users WHERE userID = '$getuser'");
	
	if (mysql_num_rows($userq) > 0)
	{
		$userr = mysql_fetch_array($userq);
		$tutuser = $userr['uid'];
?>
	<div class="single-center">
		<h1><?=$tutuser?>'s Tutorials</h1>
		<h2><a href="tutorial.php">Tutorials</a> > <?=$tutuser?>'s Tutorials</h2>	
		<table class="tutorial-index">
			<tr>
				<th>Category</th>
				<th>Title</th>
				<th>Description</th>
				<th></th>
			</tr>
<?

	$result = mysql_query("SELECT tutorials.*,tutorialcats.name AS catname FROM tutorials LEFT JOIN tutorialcats ON catID = sectionID WHERE authorid = '$getuser' AND waiting = '0' AND tutorialcats.level = 0 ORDER BY tutorialID ASC");
	while($row = mysql_fetch_array($result))
	{
		$diff="hard";
		if ($row['difficulty'] == 0)
			$diff = "easy";
		elseif ($row['difficulty'] == 1)
			$diff = "medium";
		?>
			<tr>
				<td align="left"><a href="tutorial.php?cat=<?=$row['catID']?>"><?=$row['catname']?></a></td>
				<td><a href="tutorial.php?id=<?=$row['tutorialID']?>"><?=$row['name']?></a></td>
				<td align="left"><?=$row['description']?></td>
				<td><img src="images/tut_<?=$diff?>.png" alt="easy" /></td>
			</tr>
		<?
	}
?>
		</table>		
	</div>
<?
	}
	else fail("User not found","tutorial.php");
?>