<?php

// get various _GET values
$getthread=mysql_real_escape_string($_GET['thread']);
$getpage=mysql_real_escape_string($_GET['page']);

// get the forum
$getid = -1;
$findingcat = mysql_query("SELECT forumcat FROM threads WHERE threadID = '$getthread' LIMIT 1");
if (mysql_num_rows($result) > 0)
{
	$gettingcat = mysql_fetch_array($findingcat);
	$getid = $gettingcat['forumcat'];
}

// get the level of the forum to ensure user has access - if not logged on, can only access level 0 forums
$level = -1;
$result = mysql_query("SELECT * FROM forumcats WHERE forumID='$getid'");
if (mysql_num_rows($result) > 0)
{
	$row = mysql_fetch_array($result);
	$level = $row['accesslevel'];
}

// posts per page. this can be specified by the user, but is 50 by default.
$postcount = 50;
// just declaring here from habit. i've been doing java at uni this semester.
$startat = 0;
// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
$page = 1;
//small avatars
$small_avs = false;

//user defined post count
if (isset($forumposts) && $forumposts >= 10 && $forumposts <= 50 && is_numeric($forumposts) && (($forumposts%10) == 0)) $postcount = $forumposts;
//user defined avatars
if (isset($forumavs)) $small_avs = $forumavs;


if (($level!=-1) && 
isset($getthread) && ($getthread!="")&& is_numeric($getthread) && 
((isset($_SESSION['uid']) && $_SESSION['lvl']>=$level) || ($level=="0")))
{
	// get the id of the last post in the thread, if it was posted by user, then they can edit that post
	$finaledit = 0;
	$zero = mysql_query("SELECT postID,posterid,postdate FROM posts WHERE threadid = '$getthread' ORDER BY postID DESC LIMIT 1");
	$one=mysql_fetch_array($zero);
	$lastpostdate = $one['postdate'];
	if (isset($_SESSION['usr']) and ($one['posterid']==$_SESSION['usr'])) 
	{
		$finaledit=$one['postID'];
	}
	
	$url = "forums.php?thread=$getthread&amp;page=";
	$genind = generateindex("page",$postcount,"SELECT * FROM posts WHERE threadid = '$getthread'",5,$url);
	$page_index = $genind[0];
	$startat = $genind[1];
	
	// find the names of the thread and forum, and see if the thread is closed
	$getinfo = mysql_query("SELECT prop_open,threads.name AS thread,forumcats.name AS forum
	FROM threads LEFT JOIN forumcats ON threads.forumcat = forumID WHERE threadid = '$getthread' LIMIT 1");
	$getinfo2=mysql_fetch_array($getinfo);
	$forumname=$getinfo2['forum'];
	$threadname=$getinfo2['thread'];
	$isopen=$getinfo2['prop_open'];
	
	?>
		<div class="single-center">
			<h1><?=$threadname?></h1>
			<span class="page-index">
				<?=$page_index?>
			</span>	
			<h2 id="forum-top"><a href="forums.php">Forums</a> > <a href="forums.php?id=<?=$getid?>"><?=$forumname?></a><?=($_SESSION['lvl'] >= 35)?' > [<a href="forums.php?editthread='.$getthread.'">M</a> | <a href="forumtrackthread.php?id='.$getthread.'">T</a>]':((isset($_SESSION['lvl']) && $_SESSION['lvl']!="")?' > [<a href="forumtrackthread.php?id='.$getthread.'">T</a>]':'')?></h2>
			<div class="forums">
	<?
	
	//add 1 to the number of views of the page
	mysql_query("UPDATE threads SET stat_views = stat_views+1 WHERE threadID = '$getthread'");
	
	if (isset($_SESSION['usr']) && $_SESSION['usr']!="")
	{
		// add viewtime to session
		$_SESSION['threads'][$getthread] = gmt("U");
		//if thread is tracked, mark thread as viewed to this user
		mysql_query("UPDATE threadtracking SET isnew = '0' WHERE trackuser = '$usr' AND trackthread = '$getthread'");
	}
	
	
	// get info about the post, the user, thread, forum etc
	$result = mysql_query("SELECT postID,userID,uid,avtype,lvl,allowtitle,usetitle,title,postdate,postedit,posttext,levelname FROM posts LEFT JOIN users ON posts.posterid = users.userID LEFT JOIN userlevels ON levelnum = lvl WHERE threadid = '$getthread' ORDER BY postID ASC LIMIT $startat,$postcount");
/*
	$result = mysql_query("SELECT
	postID,threads.threadID,forumID,userID,uid,avtype,lvl,postdate,postedit,posttext,threads.name AS thread,forumcats.name AS forum
	FROM posts
	LEFT  JOIN threads ON posts.threadid = threads.threadID
	LEFT  JOIN forumcats ON posts.forumcat = forumcats.forumID
	LEFT JOIN users ON posts.posterid = users.userID
	WHERE threads.threadID = '$getthread'
	ORDER BY postdate ASC LIMIT $startat,$postcount");
*/
	
	// for alternating post backgrounds
	$alt = true;
	
	while($row = mysql_fetch_array($result))
	{
		// why am i bothering to comment this part?
		$poster=$row['userID'];
		$postname=$row['uid'];
		$avtype=$row['avtype'];
		$userlvl=$row['lvl'];
		$userlvlname=$row['levelname'];
		
		if (($row['allowtitle'] > 0 || $row['lvl'] >= 20) && $row['usetitle'] > 0 && $row['title'] != "") $userlvlname = $row['title'];
		
		$postid=$row['postID'];
		$ptext=$row['posttext'];
		$pdate1=$row['postdate'];
		$pdate2=$row['postedit'];
		
		$alt = !$alt;
		
		// if user is a mod, let them delete and edit all posts. otherwise, users can edit their last posts.
		$delpost = "";
		if (isset($_SESSION['uid']) && $_SESSION['lvl']>=35) {
			$delpost='[<a href="forums.php?editpost=' . $postid . '">E</a>]|[<a href="forums.php?deletepost=' . $postid . '">D</a>]';
		}
		elseif ($finaledit != 0 and $finaledit == $postid) {
			$delpost='[<a href="forums.php?editpost=' . $finaledit . '">Edit</a>]';
		}
		
		// for alternate classes
		$addalt = "";
		if ($alt) $addalt = "-alt";		
		
		// do some not-so-weird thing with avatars
		//$avatar=getavatar($poster,$avtype,true);
		// temporary replacement
		$avatar=getavatar($poster,$avtype,$small_avs);
		
		// date format = 1 Nov 07 23:00 (time this comment was written, Australian Eastern Standard Time :P )
		$pdate=timezone($pdate1,$_SESSION['tmz'],"d M y, H:i");
		
		// render the post
		?>
		<div class="post-container<?=$addalt?>">
			<div class="post-container<?=$addalt?>-info">
				<?=$pdate?><br />
				By <a href="user.php?id=<?=$poster?>"><?=$postname?></a> <?=$delpost?> <br />
				<img src="<?=$avatar?>" alt="avatar" /><br />
				<?=$userlvlname?>
			</div>	
			<div class="post<?=$addalt?>">
				<?=post_format($ptext,false,$userlvl)?>
			</div>
		</div>
		<?
	}
		// random trivia, the smiley for the day is ;o
	?>	
	</div>
	<span class="page-index" id="forum-page-index-bottom">
		<?=$page_index?>
	</span>	
	<h2 id="forum-bottom"><a href="forums.php">Forums</a> > <a href="forums.php?id=<?=$getid?>"><?=$forumname?></a><?=($_SESSION['lvl'] >= 35)?' > [<a href="forums.php?editthread='.$getthread.'">M</a> | <a href="forumtrackthread.php?id='.$getthread.'">T</a>]':((isset($_SESSION['lvl']) && $_SESSION['lvl']!="")?' > [<a href="forumtrackthread.php?id='.$getthread.'">T</a>]':'')?></h2>
</div>	
	<?php
	if (isset($_SESSION['lvl']) && ($isopen) && ($lastpostdate < strtotime("-3 months",gmt("U"))) && ($_SESSION['lvl'] < 35))
	{
	?>
		<div class="single-center" id="gap-fix-bottom">
			<h1>Old Thread</h1>
			<div class="closed">You cannot reply to threads that have been inactive for more than three months. If you really want to post in this thread, you can submit a <a href="forums.php?threadbump=<?=$getthread?>">Thread Bump Request</a>. It is recommended that you just create a new thread.</div>
		</div>
	<?
	}
	elseif (isset($_SESSION['lvl']) && ($isopen))
	{
	?>
		<div class="single-center" id="gap-fix-bottom">
			<h1>Post a Reply</h1>
			<form action="forumaddpost.php?id=<?=$getthread?>" method="post">
				<div class="smilies" id="smiley-toggle"><a href="javascript:toggle('smiley')">[Show Smilies]</a></div>
				<div class="smilies" id="smiley-content"><? include 'smilies.php'; ?></div>
				<fieldset class="new-thread">
				<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
				<div class="smilies" id="bb-content">
					<? include 'bbcode.php'; ?>
				</div>
					<fieldset style="text-align: center;">
						<textarea id="newposttext" rows="10" cols="76" name="posttxt"></textarea>
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
						<input name="close" style="margin-left: 5px;" type="checkbox" /> Close thread after posting
					</p>
					<? } ?>
				</fieldset>
			</form>
		</div>
	<?
	}
	elseif (isset($_SESSION['lvl']) && (!$isopen))
	{
	?>
		<div class="single-center" id="gap-fix-bottom">
			<h1>Thread Closed</h1>
			<div class="closed">This thread is closed to new replies.<?=($_SESSION['lvl']>=35)?' Unlock the thread from the <a href="forums.php?editthread='.$getthread.'">thread moderation page</a>.':''?></div>
		</div>
	<?
	}
	else
	{
	?>
		<div class="single-center" id="gap-fix-bottom">
			<h1>Login to Reply</h1>
			<div class="closed">You must be logged in to reply.</div>
		</div>
	<?
	}
	
}
else
{
	if (!isset($getthread) || ($getthread=="") || !is_numeric($getthread))
	{
		$problem = "There is no thread specified.";
		$back = "forums.php";
	}
	elseif ($level==-1)
	{
		$problem = "You are not authorised to view this forum, or it doesn't exist.";
		$back = "forums.php";
	}
	elseif ((isset($_SESSION['uid']) && ($_SESSION['lvl']<$level)) || (!isset($_SESSION['uid']) && ($level>0)))
	{
		$problem = "You are not authorised to view this forum, or it doesn't exist.";
		$back = "forums.php";
	}
	include 'failure.php';
}
?>
