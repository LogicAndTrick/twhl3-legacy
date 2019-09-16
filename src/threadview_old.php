<div id="forum-layout">
<?php

// get various _GET values
$getid=mysql_real_escape_string(trim($_GET['id']));
$getthread=mysql_real_escape_string(trim($_GET['thread']));

// get the level of the forum to ensure user has access - if not logged on, can only access level 0 forums
$result = mysql_query("SELECT * FROM forumcats WHERE forumID='$getid'");
$row = mysql_fetch_array($result);
$level=$row['accesslevel'];

if ((isset($_SESSION['uid']) and $_SESSION['lvl']>=$level) or ($level=="0"))
{
	// get the id of the last post in the thread, if it was posted by user, then they can edit that post
	$finaledit = 0;
	$zero = mysql_query("SELECT postID,posterid FROM posts WHERE forumcat='$getid' AND threadid = '$getthread' ORDER BY postID DESC LIMIT 1");
	$one=mysql_fetch_array($zero);
	if (isset($_SESSION['usr']) and ($one['posterid']==$_SESSION['usr'])) 
	{
		$finaledit=$one['postID'];
	}

	// get info about the post, the user, thread, forum etc
	$result = mysql_query("SELECT
	postID,threads.threadID,forumID,userID,uid,avtype,lvl,postdate,postedit,posttext,threads.name AS thread,forumcats.name AS forum
	FROM posts
	LEFT  JOIN threads ON posts.threadid = threads.threadID
	LEFT  JOIN forumcats ON posts.forumcat = forumcats.forumID
	LEFT JOIN users ON posts.posterid = users.userID
	WHERE forumID = '$getid' AND threads.threadID = '$getthread'
	ORDER BY postdate ASC LIMIT 50");
	
	// cheap way to supress yet another sql query to get the forum/thread names - they are retrieved in the query above
	$once = true;
			
	while($row = mysql_fetch_array($result))
	  {
		// why am i bothering to comment this part?
		$poster=$row['userID'];
		$postname=$row['uid'];
		$avtype=$row['avtype'];
		$userlvl=$row['lvl'];
		
		$postid=$row['postID'];
		$ptext=$row['posttext'];
		$pdate1=$row['postdate'];
		$pdate2=$row['postedit'];
			
		// you'll probably see from the var name, but this code is only executed once in this loop
		if ($once) {
			$forumname=$row['forum'];
			$threadname=$row['thread'];
			echo '<div class="post-title"><b><a href="forums.php">Forums</a> &raquo; <a href="forums.php?id=' . $getid . '">' . $forumname . '</a> &raquo; ' . $threadname . '</b></div>';
			$once = false;
		}
		// if user id a mod, let them delete and edit all posts. otherwise, users can edit their last posts.
		$delpost = "";
		if (isset($_SESSION['uid']) and $_SESSION['lvl']>2) {
			$delpost='<a href="editpost.php?delete=' . $postid . '">Delete</a>/<a href="editpost.php?edit=' . $postid . '">Edit</a>';
		}
		elseif ($finaledit != 0 and $finaledit == $postid) {
			$delpost='<a href="editpost.php?edit=' . $finaledit . '">Edit</a>';
		}
		
		// do some weird thing with avatars, i haven't looked at the function and i dont know why it doesn't do the second line automatically.
		$avatar=getavatar($poster,$avtype);
		
		// date format = 1 Nov 07 23:00 (time this comment was written, Australian Eastern Standard Time :P )
		$pdate=date(d,$pdate1) . " " . date(M,$pdate1) . " " . date(y,$pdate1) . " " . date(H,$pdate1) . ":" . date(i,$pdate1);
		
		// render the post, note this is subject to change once the designers get off their asses.
		echo '<div class="post-wrapper">

					<p class="post-author">
						Posted by <a href="user.php?id=' . $poster . '" class="posted-by">' . $postname . '</a> ' . $pdate . '<br />
						<img src="' . $avatar . '" alt="avatar" />
						<span class="title">' . axslvl($userlvl) .  '</span>' . $delpost . '
					</p>
					<p class="post-content">
							' . post_format($ptext,false,$userlvl) . '
						<span class="clearfix">&nbsp;</span>
					</p>
				</div>
			';
			// random trivia, the smiley for the day is ;o
	  }

	?>
				</div>		
	<?php
	if (isset($_SESSION['lvl']))
		echo '<div id="forum-textbox"><form action="addpost.php?id=' . $_GET['id'] . '&thread='. $_GET['thread'] . '" method="post">
				<textarea rows="10" cols="50" name="posttxt"></textarea><br />
				<input type="submit" value="Post!" size="7" />
			</form></div>';
	
}
else
{
	// NO SOUP FOR YOU
	echo 'LOL NO ACCESS';
	echo '</div>';
}
?>
		
