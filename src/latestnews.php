<div class="center-content">
	<h1 class="no-bottom-border"><a href="news.php">News</a></h1>
<?php

	$result = mysql_query("SELECT newsID,userID,uid,lvl,news.title,date,newsart,stat_coms,avtype,levelname,allowtitle,usetitle,users.title AS usertitle FROM news LEFT JOIN users ON newscaster = userID LEFT JOIN userlevels on lvl = levelnum ORDER BY newsID DESC LIMIT 2");

	while($row = mysql_fetch_array($result))
	{
		$avst=getresizedavatar($row['userID'],$row['avtype'],100);
		
		$userlvlname=$row['levelname'];
		if (($row['allowtitle'] > 0 || $row['lvl'] >= 20) && $row['usetitle'] > 0 && $row['usertitle'] != "") $userlvlname = $row['usertitle'];
		
?>
	<span class="date"><?=timezone($row['date'],$_SESSION['tmz'],"F jS, Y")?></span>
	<h2 class="top-border"><?=$row['title']?></h2>
	<span class="news-info">
		<img src="<?=$avst?>" alt="<?=$row['uid']?>" /><br />
		<a href="user.php?id=<?=$row['userID']?>"><?=$row['uid']?></a><br />
		<?=$userlvlname?><br />
		<a href="news.php?id=<?=$row['newsID']?>">[<?=$row['stat_coms']?> comment<?=($row['stat_coms']==1)?'':'s'?>]</a><?=(isset($_SESSION['uid']) and $_SESSION['lvl']>2)?'<br />'."\n".'<a href="news.php?edit='.$row['newsID'].'">Edit</a>/<a href="news.php?delete='.$row['newsID'].'">Delete</a>':''?>
	</span>
	<p class="news-content">
		<?=news_format($row['newsart'])?>
	</p>
<?
	}
	if (isset($lvl) && $lvl >= 35)
	{
?>
	<div class="news-menu">
		<a href="news.php?post">Post News</a>
	</div>
<?
	}
?>
</div>