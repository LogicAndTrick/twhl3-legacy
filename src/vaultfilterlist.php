<?
	//========================================================================
	$getgames=mysql_real_escape_string($_GET['games']);
	$gamelist="0";
	$iszero = false;
	$games = explode(",",$getgames);
	$gamelist = "";
	foreach ($games as $game)
	{
		$game = mysql_real_escape_string($game);
		$gamelist .= " OR game = '$game'";
		if ($game == '0' || $game == '') $iszero = true;
	}
	$gamelist = substr($gamelist,4);
	if ($iszero || $gamelist == "") $gamelist = 'game > 0';
	$gamelist = "($gamelist)";
	//========================================================================
	$getcats=mysql_real_escape_string($_GET['cats']);
	$iszero = false;
	$cats = explode(",",$getcats);
	$catlist = "";
	foreach ($cats as $cat)
	{
		$cat = mysql_real_escape_string($cat);
		$catlist .= " OR cat = '$cat'";
		if ($cat == '0' || $cat == '') $iszero = true;
	}
	$catlist = substr($catlist,4);
	if ($iszero || $catlist == "") $catlist = 'cat > 0';
	$catlist = "($catlist)";
	//=======================================================================
	$getincs=mysql_real_escape_string($_GET['inc']);
	$iszero = false;
	$incs = explode(",",$getincs);
	$inclist = "";
	foreach ($incs as $inc)
	{
		$inc = mysql_real_escape_string($inc);
		$inclist .= " OR included = '$inc'";
		if ($inc == '0' || $inc == '') $iszero = true;
	}
	$inclist = substr($inclist,4);
	if ($iszero || $inclist == "") $inclist = 'included > -1';
	$inclist = "($inclist)";
	//=======================================================================
	$minrating = mysql_real_escape_string($_GET['min']);
	if (!is_numeric($minrating) || $minrating > 5 || $minrating < 0) $minrating = 0;
	//=======================================================================
	$sortorder = mysql_real_escape_string($_GET['sort']);
	if ($sortorder != 'postdate' && $sortorder != 'avgrating' && $sortorder != 'ratings' && $sortorder != 'views' && $sortorder != 'downloads') $sortorder = 'postdate';
	//=======================================================================
	$ascdesc = mysql_real_escape_string($_GET['arr']);
	$ascdesc=strtoupper($ascdesc);
	if ($ascdesc != 'ASC' && $ascdesc != 'DESC') $ascdesc = 'DESC';
	$ascdesc=strtoupper($ascdesc);
	//=======================================================================
	
	// maps per page.
	$mapcount = 18;
	// just declaring here from habit. i've been doing java at uni this semester.
	$startat = 0;
	// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
	$page = 1;
	
	$getpage=trim($_GET['page']);

	// get the number of threads in the whole forum - for page index
	$checkpageq = mysql_query("SELECT count(*) AS cnt FROM maps WHERE $gamelist AND $catlist AND $inclist AND ifnull((ceil((rating/ratings)*2))/2,0) >= $minrating");
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
	
	$url = "vault.php?advfilter=1&games=".(($getgames != '')?$getgames:'0')."&cats=".(($getcats != '')?$getcats:'0')."&inc=".(($getincs != '')?$getincs:'0')."&min=$minrating&sort=$sortorder&arr=".strtolower($ascdesc)."&page=";
	
	$mod = false;
	if ($_SESSION['lvl'] >= 30) $mod = true;


	$result = mysql_query("SELECT maps.*, uid, engineID,(ceil((rating/ratings)*2))/2 AS avgrating FROM maps
LEFT JOIN users ON owner = userID
LEFT JOIN mapgames on game = gameID
LEFT JOIN mapengines on engine = engineID
WHERE $gamelist AND $catlist AND $inclist AND ifnull((ceil((rating/ratings)*2))/2,0) >= $minrating
ORDER BY $sortorder $ascdesc, ratings DESC, mapID DESC
LIMIT $startat,$mapcount");
	
	$counter = 0;

?>


<div class="single-center">
	<h1>Vault Advanced Filter</h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2><a href="vault.php">Map Vault</a> > <a href="vault.php?advfilter=0">Advanced Filter</a></h2>
	<div class="map-order">
		<a href="vault.php?advfilter=0">Filter:</a> <span class="filter">Custom Filter</span> - Order by: <span class="sort"><?=ucfirst($sortorder).' '.strtolower($ascdesc).'ending'?></span>
	</div>
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
					<a href="vault.php?map=<?=$row['mapID']?>"><?=$row['name']?></a><? if ($mod) { ?> <a href="vault.php?edit=<?=$row['mapID']?>">[M]</a><? } ?><br />
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
					<a href="vault.php?map=<?=$row['mapID']?>"><img src="mapvault/<?=($row['screen'])?$row['mapID'].'_small.jpg':'noscreen_small.png'?>" alt="map image" /></a>
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
	<? } else { ?>
	<div class="sorry">There are no maps in the category specified</div>
	<? } ?>
	<span class="page-index" id="forum-page-index-bottom">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2 id="forum-bottom"><a href="vault.php">Map Vault</a> > <a href="vault.php?advfilter=0">Advanced Filter</a></h2>
</div>
<? if (isset($_SESSION['lvl']) && $_SESSION['lvl']!="") { ?>
<div class="single-center" id="gap-fix-bottom">
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
<?
}
?>