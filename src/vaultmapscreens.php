<?
	$getmap = mysql_real_escape_string($_GET['screens']);
	$mapq = mysql_query("SELECT * FROM maps LEFT JOIN mapcats ON cat = catID WHERE mapID = '$getmap' AND cat != -1");
	if (mysql_num_rows($mapq) == 0) fail("Map doesn't exist!","vault.php");
	$mar = mysql_fetch_array($mapq);
	$mapname = $mar['name'];
	$cat = $mar['cat'];
	$catname = $mar['catname'];
	$screenq = mysql_query("SELECT * FROM mapscreens WHERE screenmap = '$getmap'");
?>
<div class="single-center" style="margin-bottom: 0;">
	<h1>Additional Screenshots</h1>
	<h2><a href="vault.php">Map Vault</a> &gt; <a href="vault.php?id=<?=$cat?>"><?=$catname?></a> &gt; <a href="vault.php?map=<?=$getmap?>"><?=$mapname?></a> &gt; Additional Screenshots</h2>
	<p class="single-center-content">
		On this page, all additional screenshots for a map are displayed. Click one to view the full version.
	</p>
<? if (mysql_num_rows($screenq) > 0) { ?>
	<table style="border: 0; width: 657px;">
<?
	while($scr = mysql_fetch_array($screenq)) {
		if ($counter == 0) {
			echo '<tr>';
		}
		
		$screendate=timezone($scr['screendate'],$_SESSION['tmz'],"d M y");
		$screenthumb=$scr['screenthumb'];
		$screenlink=$scr['screenlink'];

?>
			<td style="text-align: center;"><a href="<?=$screenlink?>"><img src="<?=$screenthumb?>" alt="Screenshot" /></a><br /><span>Added on <?=$screendate?></td>
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
	<div class="sorry">There are no additional screenshots for this map yet.</div>
<? } ?>
</div>