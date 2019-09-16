<?
	$mod = false;
	if ($_SESSION['lvl'] >= 30) $mod = true;

	$lastmonth = date("n",strtotime("last month",gmt("U")));
	$lastmonthyear = date("Y",strtotime("last month",gmt("U")));
	
	$more = "";
	if (isset($usr) && $usr!="") $more = "AND owner != '$usr' ";
	
	$result = mysql_query("SELECT maps.*, uid, engineID FROM maps LEFT JOIN users ON owner = userID LEFT JOIN mapgames on game = gameID LEFT JOIN mapengines on engine = engineID WHERE cat = '2' AND MONTH(FROM_UNIXTIME(postdate)) = '$lastmonth' AND YEAR(FROM_UNIXTIME(postdate)) = '$lastmonthyear' ".$more."ORDER BY postdate DESC") or die(mysql_error());
	
	$counter = 0;

?>
<div class="single-center">
	<h1>Map of the Month Voting</h1>
	<h2>Map of the Month</h2>
	<p class="single-center-content">
		Welcome to the Map of the Month Candidate List. This page is designed for you to easily see all maps eligible for Map of the Month right now. This page will always list every map from last month that was posted in the 'Completed Maps' category in the vault. To cast your vote, simply click the "Vote" button under the screenshots of the maps, or you can click on the map to go to the map page, and you can vote from there. All votes are silent, and nobody else can see which map you voted for. Remember you can also vote from the regular vault too, this is just a way to see every map available for nomination.
	</p>
</div>
<div class="single-center" style="margin-top: 0;">
	<h1>Map of the Month Candidates</h1>
	<h2><a href="vault.php">Map Vault</a> &gt; Candidates for Map of the Month</h2>
	
	<? if (mysql_num_rows($result) > 0) { ?>
	<table class="map-page">
	<?
	while($row = mysql_fetch_array($result)) {
		if ($counter == 0) {
			echo '<tr>';
		}
		
		$pdate=timezone($row['postdate'],$_SESSION['tmz'],"H:i, d M y");
		$rating = 0;
		if ($row['ratings'] > 0)
			$rating = ceil((($row['rating']/$row['ratings'])*2))/2;
		
		$numfullstars = substr($rating,0,1);
		$halfstar = (strlen($rating) > 2);
		
	?>
			<td>
				<p class="map-name">
					<a href="vault.php?map=<?=$row['mapID']?>"><?=$row['name']?></a><br />
					<? 
					if ($row['allowrating']==1) { 
					for ($i = 0; $i < $numfullstars; $i++) { ?>
					<img src="images/star_full.png" alt="star" />
					<? } if ($halfstar) { ?><img src="images/star_half.png" alt="star" /><? } ?>
					<?=($row['ratings']==0)?"No Votes Yet":"(".$row['ratings'].")"?>
					<? } else { ?>Ratings Disabled<? } ?>
				</p>
				<div style="text-align: center;"><img src="images/game_<?=$row['game']?>.png" alt="Game Icon" /></div>
				<p class="map-image">
					<a href="vault.php?map=<?=$row['mapID']?>"><img src="mapvault/<?=($row['screen'] > 0)?$row['mapID'].'_small.'.(($row['screen'] == 2)?'png':'jpg'):'noscreen_small.png'?>" alt="map image" /></a>
				</p>
				<p class="map-info">
					<? if (isset($usr) && $usr!="") { ?><a href="motmaddvote.php?id=<?=$row['mapID']?>" title="Nominate for MOTM"><img src="images/motmvote.png" alt="Nominate this Map for Map of the Month" /></a><br /><? } ?>
					By: <a href="user.php?id=<?=$row['owner']?>"><?=$row['uid']?></a><br />
					<?=$pdate?>
				</p>	
			</td>
	<?
		$counter++;
		if ($counter >= 3) {
			$counter = 0;
			echo '</tr>';
		}
	}
	if ($counter > 0) {
		echo '</tr>';
	}
	?>
	</table>
	<? } else { ?>
	<div class="sorry">There are no maps in the category specified</div>
	<? } ?>
	<h2 id="forum-bottom"><a href="vault.php">Map Vault</a> &gt; Map of the Month Candidates</h2>
</div>