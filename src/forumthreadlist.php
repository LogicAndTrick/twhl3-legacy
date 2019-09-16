<?
$getid=mysql_real_escape_string($_GET['id']);
$getpage=mysql_real_escape_string($_GET['page']);

$level = -1;
$result = mysql_query("SELECT * FROM forumcats WHERE forumID='$getid' AND orderindex >= 0");
if (mysql_num_rows($result) > 0)
{
	$row = mysql_fetch_array($result);
	$level = $row['accesslevel'];
	$forumname=$row['name'];
}

// threads per page. this can be specified by the user, but is 50 by default.
$threadcount = 50;
// just declaring here from habit. i've been doing java at uni this semester.
$startat = 0;
// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
$page = 1;

if (($level!=-1) && isset($getid) && ($getid!="")&& is_numeric($getid) && (isset($_SESSION['uid']) and $_SESSION['lvl']>=$level) or ($level=="0"))
{

	// get the number of threads in the whole forum - for page index
	$checkpageq = mysql_query("SELECT count(*) AS cnt FROM threads WHERE forumcat='$getid'");
	$checkpages=mysql_fetch_array($checkpageq);
	$numthreads = $checkpages['cnt'];
	
	// for example, if there are 60 threads  with 50 threads per page, 60/50 = 1.2, ceil(1.2) = 2. 2 pages. which is correct.
	$lastpage = ceil($numthreads/$threadcount);
	
	// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
	if ($getpage == "last") $page = $lastpage;
	elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
	elseif (($getpage-1)*$threadcount > $numthreads) $page = 1;
	else $page = $getpage;
	
	$startat = ($page-1)*$threadcount;
	
	$url = "forums.php?id=$getid&amp;page=";
	
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;

	$result = mysql_query("SELECT
	threads.threadID, name, ownerid, posttime, stat_replies, stat_views, stat_lastpostid, prop_open, prop_sticky, posterid, postdate, users.uid, users2.uid AS owner_uid
	FROM threads LEFT  JOIN posts ON threads.stat_lastpostid = posts.postID
	LEFT  JOIN users ON posts.posterid = users.userID
	LEFT JOIN users AS users2 ON ownerid = users2.userID
	WHERE threads.forumcat = '$getid'
	ORDER BY prop_sticky DESC, stat_lastpostid DESC
	LIMIT $startat,$threadcount");
?>
<div class="single-center">
	<h1><?=$forumname?></h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2><a href="forums.php">Forums</a></h2>	
	<table class="thread-index">
		<tbody>
			<tr>
				<th>Topic</th>
				<th style="width: 70px;">Replies</th>
				<th style="width: 30px;">Views</th>
				<th style="width: 120px;">Last Post</th>
				<? if ($mod) { ?><th style="width: 20px;">Mod</th><? } ?>
			</tr>
<?php
	while($row = mysql_fetch_array($result))
	  {
			$idz=$row['threadID'];
			$title=$row['name'];
			$owner=$row['owner_uid'];
			$ownerid=$row['ownerid'];
			$created=$row['posttime'];
			$numviews=$row['stat_views'];
			$numposts=$row['stat_replies'];
			$sticky=$row['prop_sticky'];
			$open=$row['prop_open'];
			$lastpostid=$row['posterid'];
			$lastposttime=$row['postdate'];
			$lastpost=$row['uid'];
			
			$ind = "orange";
			if (isset($_SESSION['threads'][$idz]) && $_SESSION['threads'][$idz]!="")
			{
				if ($_SESSION['threads'][$idz] > $lastposttime) $ind = "orange";
				else $ind = "green";
			}
			elseif (isset($_SESSION['lst']) && ($lastposttime > $_SESSION['lst'])) $ind = "green";
			if ($open == 0) $ind = "grey";
			
			$pre = "dot";
			if ($sticky == 1) $pre = "sticky";
			
			$pdate=timezone($lastposttime,$_SESSION['tmz'],"d M y, H:i");
			$cdate=timezone($created,$_SESSION['tmz'],"d M y, H:i");
			
			?>
				<tr>
					<td><img src="images/<?=$pre.$ind?>.png" alt="post incicator" /><a href="forums.php?thread=<?=$idz?>"><?=$title?></a>
					<p class="thread-author-info">by <a href="user.php?id=<?=$ownerid?>"><?=$owner?></a>, <?=$cdate?></p></td>
					<td align="center"><?=$numposts?></td>
					<td align="center"><?=$numviews?></td>
					<td class="last-post-info"><?=$pdate?><br />
					by <a href="user.php?id=<?=$lastpostid?>"><?=$lastpost?></a> <a href="forums.php?thread=<?=$idz?>&amp;page=last">»</a></td>
					<? if ($mod) { ?><td><a href="forums.php?editthread=<?=$idz?>">[M]</a></td><? } ?>
				</tr>
			<?
	  }
	?>
		</tbody>
	</table>
	<span class="page-index" id="forum-page-index-bottom">
		<?=makeindex($page,5,$lastpage,$url);?>
	</span>	
	<h2 id="forum-bottom"><a href="forums.php">Forums</a></h2>		
</div>		
	<?
	if (isset($_SESSION['lvl'])) {
		?>
		<div class="single-center" id="gap-fix-bottom">
			<h1>Post a New Thread</h1>
			<form action="forumaddthread.php?id=<?=$getid?>" method="post">
				<fieldset class="new-thread">
					<p class="single-center-content"> 
						<input name="threadtitle" class="right" size="50" style="margin-left: 5px;" type="text" />Name your thread:
					</p>
				</fieldset>
				<div class="smilies" id="smiley-toggle"><a href="javascript:togglesmilies()">[Show Smilies]</a></div>
				<div class="smilies" id="smiley-content"><? include 'smilies.php'; ?></div>
				<fieldset class="new-thread">
				<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
				<div class="smilies" id="bb-content">
					<? include 'bbcode.php'; ?>
				</div>
					<fieldset style="text-align: center;">
						<textarea id="newposttext" name="threadtxt" rows="10" cols="76"></textarea><br />
					</fieldset>
					<? if ($_SESSION['lvl'] >= 35) { ?>
					<div class="smilies" id="modbb-toggle"><a href="javascript:toggle('modbb')">[Show Mod BBCode]</a></div>
					<div class="smilies" id="modbb-content">
						<input value="purple" id="purple" class="bbcode" type="button" onclick="javascript:bbcode('purple')" onmouseover="javascript:over('purple')" onmouseout="javascript:out('purple')" />
						<input value="m" id="mod" class="bbcode" type="button" onclick="javascript:bbcode('mod')" onmouseover="javascript:over('mod')" onmouseout="javascript:out('mod')" />
						<input value="fail" id="fail" class="bbcode" type="button" onclick="javascript:youfail()" onmouseover="javascript:over('fail')" onmouseout="javascript:out('fail')" />
					</div>
					<? } ?>
					<input class="right" id="post-thread" value="Post" type="submit" />
					<? if ($_SESSION['lvl'] >= 35) { ?>
					<p class="single-center-content"> 
						<input name="sticky" style="margin-left: 5px;" type="checkbox" /> Sticky 
						<input name="announce" style="margin-left: 5px;" type="checkbox" /> Announcement
					</p>
					<? } ?>
				</fieldset>
			</form>
		</div>
		<?
	}
}
else
{
	if (!isset($getid) || ($getid=="") || !is_numeric($getid))
	{
		$problem = "There is no forum specified.";
		$back = "forums.php";
		include 'failure.php';
	}
	elseif ($level==-1)
	{
		$problem = "You are not authorised to view this forum, or it doesn't exist.";
		$back = "forums.php";
		include 'failure.php';
	}
	elseif ((isset($_SESSION['uid']) && ($_SESSION['lvl']<$level)) || (!isset($_SESSION['uid']) && ($level>0)))
	{
		$problem = "You are not authorised to view this forum, or it doesn't exist.";
		$back = "forums.php";
		include 'failure.php';
	}
	else
	{
		include 'failure.php';
	}
}
?>



