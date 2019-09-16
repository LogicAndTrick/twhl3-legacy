<div class="single-center">
	<h1>The TWHL Map Vault</h1>
	<p class="single-center-content">
		Welcome to the TWHL Map Vault! From here, you can upload maps that you're working on, you need help on, or completed maps here for the entire community to take a look at. Select from the four main categories here. Once you're in one of the categories, you can filter through the maps by game. If you're looking for a specific map, use the Search function above.	
	</p>
	<p class="single-center-content">
		First, some rules:
	</p>
	<ul>
		<li>Keep in mind that we have a diverse range of work in here. Don't just berate the newbies: help them out.</li>
		<li>Map Vault Moderators will target and root out any troublemakers in here. You've been warned!</li>		
	</ul>	
</div>

<?

	$lastmonth = date("n",strtotime("last month",gmt("U")));
	$lastmonthyear = date("Y",strtotime("last month",gmt("U")));
	
	$motmvaultnum = mysql_num_rows(mysql_query("SELECT maps.*, uid, engineID FROM maps LEFT JOIN users ON owner = userID LEFT JOIN mapgames on game = gameID LEFT JOIN mapengines on engine = engineID WHERE cat = '2' AND MONTH(FROM_UNIXTIME(postdate)) = '$lastmonth' AND YEAR(FROM_UNIXTIME(postdate)) = '$lastmonthyear' ORDER BY postdate DESC"));

	$result = mysql_query("SELECT catID, catopen, count( mapID )  AS cnt, mapID, name, postdate, owner, uid FROM (SELECT mapID,name,owner,postdate,cat FROM maps ORDER BY mapID DESC) as mq LEFT  JOIN mapcats ON cat = catID LEFT JOIN users ON owner = userID GROUP  BY catopen HAVING catID > 0 AND catopen = 1");
	$row = mysql_fetch_array($result);
	$ind = "orange";
	if (isset($_SESSION['lst']) && ($row['postdate'] > $_SESSION['lst'])) $ind = "green";
?>
<div class="single-center" id="gap-fix">
	<h1>Categories</h1>
	<div class="mapvault-index">
		<div class="mapvault-index-container">
			<span class="mapvault-info">
				<?=$row['cnt']?> maps
			</span>
			<span class="mapvault-name">
				<img src="images/dot<?=$ind?>.png" alt="indicator" /><a href="vault.php?id=all">All Maps</a>
			</span>
			<p class="mapvault-description">
				Check out every single map TWHL has to offer.
			</p>
			<p class="last-map">
				Last map: <a href="vault.php?map=<?=$row['mapID']?>"><?=$row['name']?></a>, submitted at <?=timezone($row['postdate'],$_SESSION['tmz'],"H:i, d M y")?> by <a href="user.php?id=<?=$row['owner']?>"><?=$row['uid']?></a>
			</p>
		</div>	
		
<?
$result = mysql_query("SELECT mapcats.*,count(mapID) AS cnt,mapID,name,postdate,owner,uid FROM (SELECT mapID,name,owner,postdate,cat FROM maps ORDER BY mapID DESC) AS mq LEFT JOIN mapcats ON cat = catID LEFT JOIN users ON owner = userID GROUP  BY cat HAVING catID > 0 ORDER BY catorder ASC");
while($row = mysql_fetch_array($result)) 
{

	$ind = "orange";
	if (isset($_SESSION['lst']) && ($row['postdate'] > $_SESSION['lst'])) $ind = "green";
?>
		
		<div class="mapvault-index-container">
			<span class="mapvault-info">
				<?=$row['cnt']?> maps
			</span>
			<span class="mapvault-name">
				<img src="images/dot<?=$ind?>.png" alt="indicator" /><a href="vault.php?id=<?=$row['catID']?>"><?=$row['catname']?></a>
			</span>
			<p class="mapvault-description">
				<?=$row['catdesc']?>
			</p>
			<p class="last-map">
				Last map: <a href="vault.php?map=<?=$row['mapID']?>"><?=$row['name']?></a>, submitted at <?=timezone($row['postdate'],$_SESSION['tmz'],"H:i, d M y")?> by <a href="user.php?id=<?=$row['owner']?>"><?=$row['uid']?></a>
			</p>
		</div>		
<? } ?>
		<div class="mapvault-index-container">
			<span class="mapvault-info">
				<?=$motmvaultnum?> maps
			</span>
			<span class="mapvault-name">
				<img src="images/dotblue.png" alt="indicator" /><a href="vault.php?motm">Map of the Month Candidates</a>
			</span>
			<p class="mapvault-description">
				An easy way to view all eligible maps for Map of the Month and vote for your favourite!
			</p>
		</div>	
	</div>
</div>	
<? if (isset($_SESSION['lvl']) && $_SESSION['lvl']!="") { ?>
<div class="single-center">
<h1>Upload a Map</h1>
	<div class="mapvault-index">
		<div class="mapvault-index-container">
			<span class="mapvault-name">
				<a href="vault.php?submit=1">Upload a Map</a>
			</span>
			<p class="mapvault-description">
				Ready to show the world your map? You can upload a map and place it in any of the above categories.
			</p>
		</div>
	</div>
</div>
<? } ?>