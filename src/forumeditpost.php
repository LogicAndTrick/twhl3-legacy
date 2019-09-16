<?php
	
	$id=mysql_real_escape_string($_GET['editpost']);
	
	$one=mysql_fetch_array(mysql_query("SELECT posts.*,lvl FROM posts LEFT JOIN users ON posterid = userID WHERE postID='$id' LIMIT 1"));
	$post_thread = $one['threadid'];
	$poster = $one['posterid'];
	$post_level = $one['lvl'];
	$two=mysql_fetch_array(mysql_query("SELECT * FROM threads WHERE threadID='$post_thread' LIMIT 1"));
	$last_post = $two['stat_lastpostid'];

	if (isset($_GET['deletepost']) && isset($_SESSION['uid']) && 
	(($_SESSION['lvl']>=35) && (($_SESSION['lvl']>$post_level) || ($poster == $_SESSION['usr']))))
	{
		
		$id=mysql_real_escape_string($_GET['deletepost']);
		$result = mysql_query("SELECT posts.forumcat,posts.threadid,posterid,posttext,postdate,uid,threads.name AS thname, forumcats.name AS fname FROM posts
		LEFT JOIN forumcats on posts.forumcat = forumID
		LEFT JOIN threads on posts.threadid = threads.threadID
		LEFT JOIN users on posterid = userID
		WHERE postID='$id'");
		$row = mysql_fetch_array($result);
		
		$cat=$row['forumcat'];
		$thread=$row['threadid'];
		$poster=$row['posterid'];
		$ptext=$row['posttext'];
		$pdate=$row['postdate'];
		
		$thname=$row['thname'];
		$fname=$row['fname'];
		$usernm=$row['uid'];
		
		$ptext=post_format($ptext);
		
		?>
		<div class="single-center">
				<h1>Deleting a Post</h1>
				<p class="single-center-content">
					From here, you can completely remove a post from the thread. Generally speaking, you'll want to delete posts when the subject matter is extremely offensive. If it isn't, it's generally better to <a href="forums.php?editpost=<?=$id?>">edit the post</a> so the original author gets to see why their post was edited.
				</p>	
			</div>	
			<div class="single-center" id="gap-fix">

				<h1>Delete - Post <?=$id?> by <?=$usernm?></h1>
				<h2><a href="forums.php">Forums</a> &gt; <a href="forums.php?id=<?=$cat?>"><?=$fname?></a> &gt; <a href="forums.php?thread=<?=$thread?>"><?=$thname?></a></h2>
				<p class="single-center-content"> 
					<?=$ptext?>
				</p>
				<form action="forumdeletepost.php?id=<?=$id?>" method="post">
					<p class="single-center-content-center">
						<input type="submit" value="Delete" size="7" />
					</p>
				</form>
			</div>
		<?
		
	}
	elseif (isset($_GET['editpost']) && isset($_SESSION['uid']) &&
	((($_SESSION['lvl']>=35) && (($_SESSION['lvl']>$post_level) || ($poster == $_SESSION['usr']))) ||
	(($last_post == $id) && ($poster == $_SESSION['usr']))))
	{
	
		$result = mysql_query("SELECT posts.forumcat,posts.threadid,posterid,posttext,postdate,uid,threads.name AS thname, forumcats.name AS fname FROM posts
		LEFT JOIN forumcats on posts.forumcat = forumID
		LEFT JOIN threads on posts.threadid = threads.threadID
		LEFT JOIN users on posterid = userID
		WHERE postID='$id' LIMIT 1");
		$row = mysql_fetch_array($result);
		
		$cat=$row['forumcat'];
		$thread=$row['threadid'];
		$poster=$row['posterid'];
		$ptext=$row['posttext'];
		$pdate=$row['postdate'];
		
		$thname=$row['thname'];
		$fname=$row['fname'];
		$usernm=$row['uid'];	
	
	?>
	<div class="single-center">
		<h1>Editing a Post</h1>
		<p class="single-center-content">
			From here, you can edit the content of a post.<?=($_SESSION['lvl']>=35)?" If you're making censor notes, I generally try to add a reason why you're censoring it (use [mod] followed by your message to give it the correct styling). You can also change the ID of who posted by putting a new ID number in the box. If you're not changing the ID, leave the box blank.":""?>
		</p>	
	</div>	
	<div class="single-center" id="gap-fix">

		<h1>Edit - Post <?=$id?> by <?=$usernm?></h1>
		<h2><a href="forums.php">Forums</a> &gt; <a href="forums.php?id=<?=$cat?>"><?=$fname?></a> &gt; <a href="forums.php?thread=<?=$thread?>"><?=$thname?></a></h2>
		<form action="forumchangepost.php?id=<?=$id?>" method="post">
			<div class="smilies" id="smiley-toggle"><a href="javascript:togglesmilies()">[Show Smilies]</a></div>
			<div class="smilies" id="smiley-content"><? include 'smilies.php'; ?></div>
			<fieldset class="new-thread">
			<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
			<div class="smilies" id="bb-content">
				<? include 'bbcode.php'; ?>
			</div>
				<fieldset style="text-align: center;">
					<textarea id="newposttext" name="posttxt" rows="10" cols="76"><?=$ptext?></textarea>
				</fieldset>
				<? if ($_SESSION['lvl'] >= 35) { ?>
				<div class="smilies" id="modbb-toggle"><a href="javascript:toggle('modbb')">[Show Mod BBCode]</a></div>
				<div class="smilies" id="modbb-content">
					<input value="purple" id="purple" class="bbcode" type="button" onclick="javascript:bbcode('purple')" onmouseover="javascript:over('purple')" onmouseout="javascript:out('purple')" />
					<input value="m" id="mod" class="bbcode" type="button" onclick="javascript:bbcode('mod')" onmouseover="javascript:over('mod')" onmouseout="javascript:out('mod')" />
					<input value="fail" id="fail" class="bbcode" type="button" onclick="javascript:youfail()" onmouseover="javascript:over('fail')" onmouseout="javascript:out('fail')" />
				</div>
				<? } ?>
				<input class="right" id="post-thread" type="submit" value="Edit" />
				<? if ($_SESSION['lvl'] >= 35) { ?>
				<p class="single-center-content"> 
					<input name="newid" style="margin-left: 8px;" size="4" type="text" /> Enter new ID
				</p>
				<? } ?>
			</fieldset>
		</form>
	</div>
	<?
	}
	else
	{
		if (!((isset($_GET['editpost']) && trim($_GET['editpost'])!="") || (isset($_GET['deletepost']) && trim($_GET['deletepost'])!="")))
		{
			$problem = "There is no action specified.";
			$back = "forums.php";
		}
		elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
		{
			$problem = "You don't seem to be logged in.";
			$back = "forums.php?thread=".$post_thread;
		}
		elseif (($_SESSION['lvl']>=35) && !($_SESSION['lvl']>$post_level) && !($poster == $_SESSION['usr']))
		{
			$problem = "You are only allowed to modify your own posts, or posts of users of lower level than you.";
			$back = "forums.php?thread=".$post_thread;
		}
		else
		{
			$problem = "You don't have permission to modify this post";
			$back = "forums.php?thread=".$post_thread;
		}
		include 'failure.php';
	}
?>