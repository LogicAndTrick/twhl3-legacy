<div class="single-center" style="display: <?=($currenttab=='journal')?'inline':'none'?>;" id="journal-tab">
<h1>Members | <?=$user?></h1>
	<span class="page-index">
		<a href="javascript:tabswitcher(new Array('user-tab','journal-tab','map-tab'))">User</a> |
		<span style="padding: 3px 0 3px 6px; color: black;">Journals</span> |
		<a href="javascript:tabswitcher(new Array('map-tab','user-tab','journal-tab'))">Maps</a>
	</span>	
<?
	$canmod = false;
	if (isset($lvl) && ($lvl >= 35)) $canmod = true;
	if (isset($usr) && $usrid == $usr) $canmod = true;

	$journalq = mysql_query("SELECT * FROM journals WHERE ownerID = '$usrid' ORDER BY journaldate DESC LIMIT 5");
	if (mysql_num_rows($journalq) > 0)
	{
		$alt = "-alt";
?>
	<h2 style="border-bottom: 0;">Recent Journals &gt; <a href="journals.php?user=<?=$usrid?>">View All Journals</a></h2>
	<div class="journals">
<?
		while ($jrow = mysql_fetch_array($journalq))
		{
			if ($alt == "") $alt = "-alt";
			else $alt = "";
?>
		<div class="journal-container<?=$alt?>">
			<span class="date"><a href="journals.php?id=<?=$jrow['journalID']?>"><?=$jrow['stat_coms']?> comment<?=($jrow['stat_coms']==1)?'':'s'?></a> | <?=($canmod)?'<a href="journals.php?journal='.$jrow['journalID'].'&amp;edit">Edit</a> | <a href="journals.php?journal='.$jrow['journalID'].'&amp;delete">Delete</a> | ':''?><?=timezone($jrow['journaldate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="profile-text">
				<?=bio_format($jrow['journaltext'])?>
			</div>
		</div>
<?
		}
?>
	</div>
	<p class="single-center-content-center">
		<a href="journals.php?user=<?=$usrid?>">[View All Journals]</a>
	</p>
<?
	}
	else
	{
?>
	<h2>Recent Journals</h2>
	<p class="single-center-content">
		This user has no journals.
	</p>
<?
	}
?>
</div>