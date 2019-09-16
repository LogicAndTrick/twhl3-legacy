<?
	$recmapq = mysql_query("SELECT * FROM maps LEFT JOIN users ON owner = userID ORDER BY mapID DESC LIMIT 3");
	$topmapq = mysql_query("SELECT * FROM (SELECT * FROM maps LEFT JOIN users ON owner = userID WHERE allowrating = '1' AND cat = '2' AND ratings > '5' AND (ceil((rating/ratings)*2))/2 >= '4.5' ORDER BY RAND()) as rq LIMIT 3");
?>
<div class="center-content">
	<h1 class="no-bottom-border"><a href="vault.php">From the Vault</a></h1>
	<div class="feature-map-container">
		<h2 class="top-border">Recent Maps</h2>
<?
	while ($rmr = mysql_fetch_array($recmapq))
	{
        $screen = $rmr['screen'];
        $shot = $screen > 0 ? ($rmr['mapID']. '.' .($screen == 2 ? 'png' : 'jpg')) : 'noscreen_small.png';
?>
		<div class="feature-map-thumb">
			<p><a href="vault.php?map=<?=$rmr['mapID']?>"><?=$rmr['name']?></a></p>
			<a href="vault.php?map=<?=$rmr['mapID']?>"><img alt="Screenshot" src="http://www.twhl.info/mapvault/<?=$shot?>" /></a>
			<p>By <a href="user.php?id=<?=$rmr['userID']?>"><?=$rmr['uid']?></a></p>
		</div>
<?
	}
?>
	</div>	
	<div class="feature-map-container">
		<h2 class="top-border">Top Maps</h2>
<?
	while ($tmr = mysql_fetch_array($topmapq))
	{
        $screen = $tmr['screen'];
        $shot = $screen > 0 ? ($tmr['mapID']. '.' .($screen == 2 ? 'png' : 'jpg')) : 'noscreen_small.png';
?>
		<div class="feature-map-thumb">
			<p><a href="vault.php?map=<?=$tmr['mapID']?>"><?=$tmr['name']?></a></p>
			<a href="vault.php?map=<?=$tmr['mapID']?>"><img alt="Screenshot" src="/mapvault/<?=$shot?>" /></a>
			<p>By <a href="user.php?id=<?=$tmr['userID']?>"><?=$tmr['uid']?></a></p>
		</div>
<?
	}
?>
	</div>
</div>	
