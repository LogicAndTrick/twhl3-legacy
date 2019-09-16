<?
	if (!(isset($lvl) && ($lvl >= 25))) fail("You are not logged in, or you don't have permission to do this.","tutorial.php");
?>
<div class="single-center">
	<h1>Current Draft Tutorials</h1>
	<h2><a href="tutorial.php">Tutorials</a> &gt; View Drafts</h2>	
	<table class="tutorial-index">
		<tr>
			<th>Title</th>
			<th>Category</th>
			<th>Description</th>
			<th>Author</th>
			<th>Status</th>
			<th></th>
		</tr>
<?

	$result = mysql_query("SELECT tutorials.tutorialID,catID,tutorialcats.name AS catname,difficulty,authorid,uid,tutorials.name AS tutname,description,IFNULL(cnt,0)  AS status FROM tutorials LEFT JOIN (SELECT tutorialid,count(*) AS cnt,isactive FROM tutorialpages GROUP BY tutorialid HAVING isactive < 0) as tq ON tq.tutorialid = tutorials.tutorialID LEFT JOIN tutorialcats ON sectionID = catID LEFT JOIN users ON userID = authorid WHERE waiting > 0");
	while($row = mysql_fetch_array($result))
	{
		$diff="hard";
		if ($row['difficulty'] == 0)
			$diff = "easy";
		elseif ($row['difficulty'] == 1)
			$diff = "medium";
		?>
			<tr>
				<td><a href="tutorial.php?id=<?=$row['tutorialID']?>"><?=$row['tutname']?></a></td>
				<td><a href="tutorial.php?cat=<?=$row['catID']?>"><?=$row['catname']?></a></td>
				<td align="left"><?=$row['description']?></td>
				<td align="left"><a href="user.php?id=<?=$row['authorid']?>"><?=$row['uid']?></a></td>
				<td><?=($row['status']!=0)?'Awaiting Review':'Not Finished'?></td>
				<td><img src="images/tut_<?=$diff?>.png" alt="<?=$diff?>" /></td>
			</tr>
		<?
	}
?>
	</table>		
</div>