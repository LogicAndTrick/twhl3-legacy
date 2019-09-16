<h1>Member Sites <a href="javascript:toggleLayer('member-div');"><img id="member-div-hide" src='images/arrow_up.gif' alt="show/hide" /></a></h1>
<div id="member-div">
<?php
	$memberq = mysql_query("SELECT * FROM (SELECT * FROM users WHERE info_website != '' and lvl >= 0 ORDER BY lastlogin DESC LIMIT 20) AS mq ORDER BY RAND()");
	$sitecount = 0;
	while (($sites = mysql_fetch_array($memberq)) && ($sitecount < 2))
	{
		$siteav = getavatar($sites['userID'],$sites['avtype'],true);
		$siteurl = $sites['info_website'];
		if (strlen($sites['info_website']) > 29) $siteurl = substr($sites['info_website'],0,29) . '...';
		$siteurl = linesplitter($siteurl,16);
?>
	<p class="secondary-content">
		<span class="content-image"><a href="user.php?id=<?=$sites['userID']?>"><img src="<?=$siteav?>" alt="<?=$sites['uid']?>" /></a></span>
		<?=$sites['uid']?><br />
		<a href="http://<?=$sites['info_website']?>"><?=$siteurl?></a>
	</p>
<?
		$sitecount++;
	}

?>
</div>