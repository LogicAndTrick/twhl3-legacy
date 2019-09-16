<?
	$getpage=trim($_GET['page']);


	// maps per page.
	$mapcount = 18;
	// just declaring here from habit. i've been doing java at uni this semester.
	$startat = 0;
	// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
	$page = 1;
	
	// get the number of threads in the whole forum - for page index
	$checkpageq = mysql_query("SELECT count(*) AS cnt FROM maps WHERE gotmotm = '1'");
	$checkpages=mysql_fetch_array($checkpageq);
	$nummaps = $checkpages['cnt'];
	
	// for example, if there are 27 maps with 18 maps per page, 27/18 = 1.5, ceil(1.5) = 2. 2 pages. which is correct.
	$lastpage = ceil($nummaps/$mapcount);
	
	// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
	if ($getpage == "last") $page = $lastpage;
	elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
	elseif (($getpage-1)*$threadcount > $numthreads) $page = 1;
	else $page = $getpage;
	
	$startat = ($page-1)*$mapcount;
	
	$url = "motm.php?page=";
	
	$mod = false;
	if ($_SESSION['lvl'] >= 30) $mod = true;
?>
<div class="single-center">
	<h1>Map of the Month</h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>
	<h2>&nbsp;</h2>
	<p class="single-center-content">
		Welcome to the Map of the Month winners page! Check out everyone that's won this prestigious award as well as the comprehensive reviews for each. Remember, you've got to be in it to win it, so get mapping!
	</p>
</div>
<div class="single-center" id="gap-fix">				
	<h1>The Winners</h1>
<?
	$result = mysql_query("SELECT maps.*, uid, motmID FROM maps LEFT JOIN users ON owner = userID LEFT JOIN motm ON motm.map = mapID WHERE gotmotm = '1' ORDER BY postdate DESC LIMIT $startat,$mapcount");
	
	$counter = 0;
	
	if (mysql_num_rows($result) > 0)
	{
?>
	<table class="map-page">
<?
		while($row = mysql_fetch_array($result)) {
			if ($counter == 0) {
				echo '<tr>';
			}
			
			$pdate=timezone($row['postdate'],$_SESSION['tmz'],"F Y");
			$rating = 0;
			if ($row['ratings'] > 0)
				$rating = ceil((($row['rating']/$row['ratings'])*2))/2;
			
			$numfullstars = substr($rating,0,1);
			$halfstar = (strlen($rating) > 2);
		
?>
			<td>
				<p class="map-name">
					<a href="motm.php?id=<?=$row['motmID']?>"><?=$row['name']?></a>
				</p>
				<div style="text-align: center;"><img src="images/game_<?=$row['game']?>.png" alt="Game Icon" /></div>
				<p class="map-image">
					<a href="motm.php?id=<?=$row['motmID']?>"><img src="mapvault/<?=($row['screen'] > 0)?$row['mapID'].'_small.'.(($row['screen'] == 2)?'png':'jpg'):'noscreen_small.png'?>" alt="map image" /></a>
				</p>
				<p class="map-info">
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
<?
	}
	else
	{
?>
	<div class="sorry">There are no maps with MOTM awards yet.</div>
<?
	}
?>
</div>