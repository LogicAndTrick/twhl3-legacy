<h1>Recent Awards <a href="javascript:toggleLayer('awards-div');"><img id="awards-div-hide" src="images/arrow_up.gif" alt="show/hide" /></a></h1>
<div id="awards-div">
<?
	$awq=mysql_query("SELECT awarduser, uid, awardtypename, awardshortname FROM awards LEFT JOIN awardtypes ON awardtype = awardtypeID LEFT JOIN users ON awarduser = userID ORDER BY awardID DESC LIMIT 2");
	while ($awrow=mysql_fetch_array($awq))
	{
?>
	<p class="awards">
		<span class="content-image"><a href="user.php?id=<?=$awrow['awarduser']?>"><img src="gfx/award_<?=$awrow['awardshortname']?>_small.gif" alt="<?=$awrow['awardtypename']?> Award"/></a></span>
		<a href="user.php?id=<?=$awrow['awarduser']?>"><?=$awrow['uid']?></a>
	</p>
<?
	}
?>
</div>