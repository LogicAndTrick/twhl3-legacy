<?
	$url="journals.php?page=";
	$journcount = 30;
	$genind = generateindex("page",$journcount,"SELECT * FROM journals",5,$url);
	$startat = $genind[1];
?>
<div class="single-center">
	<h1>Journals</h1>
	<span class="page-index">
		<?=$genind[0]?>
	</span>
	<h2>User Journals</h2>
	<p class="single-center-content">
		Take a look at all the journal entries from our members.<? if (!isset($usr)) { ?> Want to contribute? <a href="register.php">Register today!</a><? } ?>
	</p>
</div>	
<?
	$journalq = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID ORDER BY journaldate DESC LIMIT $startat,$journcount");
	if (mysql_num_rows($journalq) == 0) fail("No journals found.","index.php");
	
	$alt = "-alt";
	
	$canmod = false;
	if (isset($lvl) && ($lvl >= 35)) $canmod = true;
?>
<div class="single-center" id="gap-fix-bottom">
	<h1 class="no-bottom-border">User Journals</h1>
	<div class="journals">
<?
	while ($jrow = mysql_fetch_array($journalq))
	{
		if ($alt == "") $alt = "-alt";
		else $alt = "";
		$avatar = getavatar($jrow['userID'],$jrow['avtype'],true);
?>
		<div class="journal-container<?=$alt?>">
			<span class="avatar"><img src="<?=$avatar?>" alt="avatar" /></span>
			<span class="name"><strong><a href="user.php?id=<?=$jrow['userID']?>"><?=$jrow['uid']?></a></strong></span>
			<span class="date"><a href="journals.php?id=<?=$jrow['journalID']?>"><?=$jrow['stat_coms']?> comment<?=($jrow['stat_coms']==1)?'':'s'?></a> | <?=($canmod || (isset($usr) && $usr != "" && $jrow['userID'] == $usr))?'<a href="journals.php?journal='.$jrow['journalID'].'&amp;edit">Edit</a> | <a href="journals.php?journal='.$jrow['journalID'].'&amp;delete">Delete</a> | ':''?><?=timezone($jrow['journaldate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="journal-text">
				<?=bio_format($jrow['journaltext'])?>
			</div>
		</div>
<?
	}
?>
	</div>
</div>