<h1>Member Journals <a href="javascript:toggleLayer('journals-div');"><img id="journals-div-hide" src="images/arrow_up.gif" alt="show/hide" /></a></h1>
	<div id="journals-div">	
<?php



$result = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID ORDER BY journalID DESC LIMIT 4");


while($row = mysql_fetch_array($result))
	{
		$jdate=$row['journaldate'];
		$jtext=$row['journaltext'];
		$jownerid=$row['ownerID'];
		//$jtext=revforumprocess(str_replace("<br />"," ",str_replace('<br>',' ',$jtext)));
		if (strlen($jtext)>30) $jtext=substr($jtext,0,30) . "...";
		$jtext = linesplitter($jtext,15);
		//$usernm = mysql_query("SELECT * FROM users WHERE userID = '$jownerid'") or die("Unable to verify user because : " . mysql_error());
		//$nmarray = mysql_fetch_array($usernm);
		//$jowner = $nmarray['uid'];
		$jowner = $row['uid'];
		$avatar = getavatar($row['userID'],$row['avtype'],true);
?>
	<p class="secondary-content">
		<span class="content-image"><a href="user.php?id=<?=$jownerid?>&amp;journals"><img src="<?=$avatar?>" alt="avatar" /></a></span>
		<a href="user.php?id=<?=$jownerid?>&amp;journals"><?=$jowner?></a>: <?=$jtext?><br />
		<a href="journals.php?id=<?=$row['journalID']?>">[<?=$row['stat_coms']?> comment<?=($row['stat_coms']==1)?'':'s'?>]</a>
	</p>
<?
	}



?>
</div>