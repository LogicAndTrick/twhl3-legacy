<div class="center-content">
	<h1 class="no-bottom-border"><a href="forums.php">Forums</a></h1>	
<?	
$user_lvl = 0;
if (isset($lvl) && ($lvl != "")) $user_lvl = mysql_real_escape_string($lvl);
	
$result = mysql_query("SELECT 
threads.name, posts.threadid, posts.forumcat, posterid, postdate, postedit, posttext, uid, userID, accesslevel
FROM posts
LEFT JOIN forumcats ON forumcats.forumID = posts.forumcat
LEFT JOIN threads ON posts.threadid = threads.threadID
LEFT JOIN users ON posterid = users.userID
WHERE accesslevel <= '$user_lvl'
ORDER BY postID DESC LIMIT 5");


while($row = mysql_fetch_array($result))
	{
		$forumno=$row['forumcat'];
		$level=$row['accesslevel'];
		$threadno=$row['threadid'];
		$puser=$row['posterid'];
		//$pdate1=$row['postdate'];
		//$pdate=date(d,$pdate1) . " " . date(M,$pdate1) . " " . date(y,$pdate1) . " " . date(H,$pdate1) . ":" . date(i,$pdate1);
		$pdate=timezone($row['postdate'],$_SESSION['tmz'],"d M y, H:i");
		//$ptext=revforumprocess(str_replace("<br />"," ",str_replace('<br>',' ',$row['posttext'])));
		$ptext=$row['posttext'];
		if (strlen($ptext)>150) $ptext=substr($ptext,0,150) . "...";
		$ptext=post_format($ptext,true);
		//text=forumprocess($ptext,30);
		//$ptext=latestforumprocess($ptext,30);
		//$threadnm = mysql_query("SELECT * FROM threads WHERE forumcat = '$forumno' AND threadID='$threadno'") or die("Unable to verify user because : " . mysql_error());
		//$nmarray = mysql_fetch_array($threadnm);
		//$threadname = $nmarray['name'];
		$threadname = $row['name'];
		$username=$row['uid'];
		$userid=$row['userID'];
?>
	<span class="forum-info">[By <a href="user.php?id=<?=$userid?>"><?=$username?></a>, <?=$pdate?>]</span>
	<h2 class="top-border"><a href="forums.php?thread=<?=$threadno?>&amp;page=last"><?=$threadname?></a></h2>
	<p class="content">
		<?=$ptext?>
	</p>
<?
	}



?>
</div>