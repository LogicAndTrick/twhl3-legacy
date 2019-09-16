<?/*
	$getpage = mysql_real_escape_string($_GET['log']);
	
	$url="wiki.php?log=";
	$entrycount = 50;
	$genind = generateindex("log",$entrycount,"SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleisactive = '1'",5,$url);
	$startat = $genind[1];
	
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID LEFT JOIN users ON entryuser = userID WHERE titleisactive = '1' ORDER BY entrydate DESC LIMIT $startat,$entrycount");
	if (mysql_num_rows($entyq) == 0) fail("There are no changes to view!.","wiki.php");
	
	$mod = ($lvl>=20);
?>
<div class="single-center">
	<h1>Wiki Changelog</h1>
	<span class="page-index">
		<?=$genind[0]?>
	</span>
	<h2><a href="wiki.php">Wiki</a> &gt; Changelog<? if (isset($lvl) && ($lvl >= 20)) { ?> &gt; <a href="wiki.php?admin">Admin</a><? } ?></h2>
	<p class="single-center-content">
		Welcome to the Wiki Changelog. This is where you can see a list of all recent changes to the Wiki, and who made them.
	</p>
	<table class="single-row">
		<tr>
			<th>Entry</th>
			<th>Details</th>
			<th>User</th>
			<th>Time</th>
			<th>Compare</th>
			<? if($mod) { ?><th>Chk</th><? } ?>
		</tr>
<?
	while ($entrow = mysql_fetch_array($entyq))
	{
?>
		<tr>
			<td><a href="wiki.php?id=<?=$entrow['titleID']?>"><?=$entrow['titletitle']?></a></td>
			<td><em><?=$entrow['entrydetails']?></em></td>
			<td><a href="user.php?id=<?=$entrow['userID']?>"><?=$entrow['uid']?></a></td>
			<td><?=timezone($entrow['entrydate'],$_SESSION['tmz'],"d M, y, h:ia")?></td>
			<td>(<? if ($entrow['entryrevision'] > 0) { ?><a href="wiki.php?ctl=<?=$entrow['entryID']?>">compare</a><? } else { ?>initial<? } ?>)</td>
			<? if($mod) { ?><td style="padding: 5px;"><img src="images/tut<?=($entrow['entryverified']==1)?'yes':'no'?>.png" /></td><? } ?>
		</tr>
<?
	}
?>
	</table>
</div>/*?>