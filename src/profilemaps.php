<div class="single-center" style="display: <?=($currenttab=='map')?'inline':'none'?>;" id="map-tab">
<h1>Members | <?=$user?></h1>
	<span class="page-index">
		<a href="javascript:tabswitcher(new Array('user-tab','journal-tab','map-tab'))">User</a> |
		<a href="javascript:tabswitcher(new Array('journal-tab','map-tab','user-tab'))">Journals</a> |
		<span style="padding: 3px 0 3px 6px; color: black;">Maps</span>
	</span>	
	<h2>Maps</h2>
<?
	$usermapq = mysql_query("SELECT * FROM maps WHERE owner = '$usrid' ORDER BY postdate DESC");
	if (mysql_num_rows($usermapq) > 0)
	{
?>
	<table class="map-page">
<?
		$counter = 0;
		while ($mapr = mysql_fetch_array($usermapq)) 
		{
			if ($counter == 0) {
				echo '<tr>';
			}
			$pdate=timezone($mapr['postdate'],$_SESSION['tmz'],"H:i, d M y");
			$rating = 0;
			if ($mapr['ratings'] > 0)
				$rating = ceil((($mapr['rating']/$mapr['ratings'])*2))/2;
			
			$numfullstars = substr($rating,0,1);
			$halfstar = (strlen($rating) > 2);
?>
			<td>
				<p class="map-name">
					<a href="vault.php?map=<?=$mapr['mapID']?>"><?=$mapr['name']?></a><br />
					<? 
					if ($mapr['allowrating']==1) { 
					for ($i = 0; $i < $numfullstars; $i++) { ?>
					<img src="images/star_full.png" alt="star" />
					<? } if ($halfstar) { ?><img src="images/star_half.png" alt="star" /><? } ?>
					<?=($mapr['ratings']==0)?"No Votes Yet":"(".$mapr['ratings'].")"?>
					<? } else { ?>Ratings Disabled<? } ?>
				</p>
				<p class="map-image">
					<a href="vault.php?map=<?=$mapr['mapID']?>"><img src="mapvault/<?=($mapr['screen'] > 0)?$mapr['mapID'].'_small.'.(($mapr['screen'] == 2)?'png':'jpg'):'none_small.gif'?>" alt="map image" /></a>
				</p>
				<p class="map-info">
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
<?
	}
	else
	{
?>
	<p class="single-center-content">
		This user has no maps.
	</p>
<?
	}
?>
</div>