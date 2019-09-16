<?

$getid=mysql_real_escape_string($_GET['id']);
$getpage=mysql_real_escape_string(trim($_GET['page']));

// maps per page.
$mapcount = 18;
// just declaring here from habit. i've been doing java at uni this semester.
$startat = 0;
// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
$page = 1;

$exists = false;
$result = mysql_query("SELECT * FROM mapcats WHERE catID='$getid'");
if (mysql_num_rows($result) > 0)
{
	$row = mysql_fetch_array($result);
	$exists = true;
	$catname=$row['catname'];
}
if ($getid == "0" || $getid == "all")
{
	$exists = true;
	$catname="All Maps";
	$getid = "0";
}

if (($exists) && isset($getid) && ($getid!="") && is_numeric($getid))
{
	
	$sortbygame = false;
	$orderbyrank = false;
	$sortbyengine = false;
	$sortbygameid = 0;
	$sortbyengineid = 0;
	$orderby = 'mapID DESC';
	$catcondition = "cat='$getid'";
	$condition = '';
	$getorder='map';
	$getsort='none';
	$getsortadd='';
	$filter = 'Game';
	$filterby = 'All';
	$sortorderby = 'Date';
	if ($getid == 0) $catcondition = 'cat > 0';
	if (isset($_GET['sort']) && ($_GET['sort']!=""))
	{
		if (($_GET['sort']=="game") && isset($_GET['game']) && ($_GET['game']!="") && is_numeric($_GET['game']))
		{
			$sortbygame = true;
			$sortbygameid = mysql_real_escape_string($_GET['game']);
			$condition = " AND game = '$sortbygameid'";
			$getsort='game';
			$getsortadd='&amp;game='.$sortbygameid;
			$getnmq=mysql_query("SELECT * FROM mapgames WHERE gameID = '$sortbygameid'");
			if (mysql_num_rows($getnmq) > 0)
			{
				$getnmr=mysql_fetch_array($getnmq);
				$filterby=$getnmr['gamename'];
			}
		}
		if (($_GET['sort']=="engine") && isset($_GET['engine']) && ($_GET['engine']!="") && is_numeric($_GET['engine']))
		{
			$sortbyengine = true;
			$sortbyengineid = mysql_real_escape_string($_GET['engine']);
			$condition = " AND engine = '$sortbyengineid'";
			$getsort='engine';
			$getsortadd='&amp;engine='.$sortbyengineid;
			$filter = 'Engine';
			$getnmq=mysql_query("SELECT * FROM mapengines WHERE engineID = '$sortbyengineid'");
			if (mysql_num_rows($getnmq) > 0)
			{
				$getnmr=mysql_fetch_array($getnmq);
				$filterby=$getnmr['enginename'];
			}
		}
	}
	if (isset($_GET['order']) && ($_GET['order']=="rank"))
	{
		$orderbyrank = true;
		//$orderby = 'allowrating DESC, (rating/ratings) DESC, ratings DESC, mapID DESC';
		$orderby = 'allowrating DESC, (ceil((rating/ratings)*2))/2 DESC, ratings DESC, mapID DESC';
		$getorder='rank';
		$sortorderby='Rank';
	}

	// get the number of threads in the whole forum - for page index
	$checkpageq = mysql_query("SELECT count(*) AS cnt FROM maps LEFT JOIN users ON owner = userID LEFT JOIN mapgames on game = gameID LEFT JOIN mapengines on engine = engineID WHERE $catcondition $condition");
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
	
	$url = "vault.php?id=$getid&amp;sort=$getsort".$getsortadd."&amp;order=$getorder&amp;page=";
	
	$mod = false;
	if ($_SESSION['lvl'] >= 30) $mod = true;


	$result = mysql_query("SELECT maps.*, uid, engineID FROM maps LEFT JOIN users ON owner = userID LEFT JOIN mapgames on game = gameID LEFT JOIN mapengines on engine = engineID WHERE $catcondition $condition ORDER BY $orderby LIMIT $startat,$mapcount");
	
	$counter = 0;

?>


<div class="single-center">
	<h1><?=$catname?></h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2><a href="vault.php">Map Vault</a> > [ <a href="javascript:toggle('gamefilter')">Game</a> | <a href="javascript:toggle('enginefilter')">Engine</a> ] [ <a href="vault.php?id=<?="$getid&amp;sort=$getsort".$getsortadd?>&amp;order=map">Date</a> | <a href="vault.php?id=<?="$getid&amp;sort=$getsort".$getsortadd?>&amp;order=rank">Rank</a> ]</h2>
	<div class="map-order">
		<a href="vault.php?advfilter=0">Filter</a> - <?=$filter?>: <span class="filter"><?=$filterby?></span> - Order by: <span class="sort"><?=$sortorderby?></span>
		<div class="smilies" id="gamefilter-content">
			<select id="select_filterbygame" name="game" onchange="window.location=document.getElementById('select_filterbygame').options[document.getElementById('select_filterbygame').selectedIndex].value">
				<option value="vault.php?id=<?="$getid&amp;sort=none&amp;order=$getorder"?>"<?=(!$sortbygame)?' selected="selected"':''?>>All</option>
				<?
				$res = mysql_query("SELECT * FROM mapgames ORDER BY gameorder ASC");
				while($rowg = mysql_fetch_array($res)) {
				?>
				<option value="vault.php?id=<?=$getid?>&amp;sort=game&amp;game=<?=$rowg['gameID']."&amp;order=$getorder"?>"<?=(($sortbygame) && ($rowg['gameID']==$sortbygameid))?' selected="selected"':''?>><?=$rowg['gamename']?></option>
				<?
				}
				?>
			</select>
		</div>
		<div class="smilies" id="enginefilter-content">
			<select id="select_filterbyengine" name="game" onchange="window.location=document.getElementById('select_filterbyengine').options[document.getElementById('select_filterbyengine').selectedIndex].value">
				<option value="vault.php?id=<?="$getid&amp;sort=none&amp;order=$getorder"?>"<?=(!$sortbyengine)?' selected="selected"':''?>>All</option>
				<?
				$res2 = mysql_query("SELECT * FROM mapengines ORDER BY engineorder ASC");
				while($rowe = mysql_fetch_array($res2)) {
				?>
				<option value="vault.php?id=<?=$getid?>&amp;sort=engine&amp;engine=<?=$rowe['engineID']."&amp;order=$getorder"?>"<?=(($sortbyengine) && ($rowe['engineID']==$sortbyengineid))?' selected="selected"':''?>><?=$rowe['enginename']?></option>
				<?
				}
				?>
			</select>
		</div>
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
					<a href="vault.php?map=<?=$row['mapID']?>"><img src="mapvault/<?=($row['screen'] > 0)?$row['mapID'].'_small.'.(($row['screen'] == 2)?'png':'jpg'):'noscreen_small.png'?>" alt="map image" /></a>
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
	<h2 id="forum-bottom"><a href="vault.php">Map Vault</a> > Sort By: <?=ucfirst($getsort)?> - Order by: <?=ucfirst($getorder)?></h2>
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
}
else
{
	if (!(isset($getid) && ($getid!="") && is_numeric($getid)))
	{
		$problem = "There is no category specified.";
		$back = "vault.php";	
	}
	elseif (!$exists)
	{
		$problem = "This category does not exist.";
		$back = "vault.php";
	}
	include 'failure.php';
}
?>