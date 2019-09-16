<?
	$getuser = mysql_real_escape_string($_GET['user']);
	$userq = mysql_query("SELECT * FROM users WHERE userID = '$getuser'");
	if (mysql_num_rows($userq) == 0) fail("User not found.","journals.php");
	
	$userr = mysql_fetch_array($userq);
	$journuser = $userr['uid'];

	$url="journals.php?user=$getuser&amp;page=";
	$journcount = 10;
	$genind = generateindex("page",$journcount,"SELECT * FROM journals WHERE ownerID = '$getuser'",5,$url);
	$startat = $genind[1];
	
	$journalq = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID WHERE ownerID = '$getuser' ORDER BY journaldate DESC LIMIT $startat,$journcount");
	if (mysql_num_rows($journalq) == 0) fail("No journals found.","index.php");
	
	$alt = "-alt";
	
	$canmod = false;
	if (isset($lvl) && ($lvl >= 35)) $canmod = true;
	if (isset($usr) && $getuser == $usr) $canmod = true;
?>
<div class="single-center">
	<h1>User Journals</h1>
	<span class="page-index">
		<?=$genind[0]?>
	</span>
	<h2 style="border-bottom: 0;"><?=$journuser?>'s Journals</h2>
	<div class="journals">
<?
	while ($jrow = mysql_fetch_array($journalq))
	{
		if ($alt == "") $alt = "-alt";
		else $alt = "";
		$avatar = getavatar($jrow['userID'],$jrow['avtype'],true);
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
</div>