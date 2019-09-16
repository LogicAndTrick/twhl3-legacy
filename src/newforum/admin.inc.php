<?
//---------------------------------------- ADMIN FUNCTIONS BEGIN HERE --

function create_forum() {
  if (isset($_POST['sub'])) {
    $err = false;
    $name = $_POST['name'];
    if (strlen($name) < 1 || strlen($name) > 50) {
      echo '<CENTER><FONT COLOR="red"><B>Forum name must be between 1 and 50 chars</B></FONT></CENTER>';
      $err = true;
    }
    if (!$err) {
      mysql_query('INSERT INTO twhl_forums (forum_name) VALUES ("'.$name.'")',$GLOBALS['dbh']) or die('Database error');
      echo '<CENTER><FONT COLOR="green"><B>Done!</B> <A HREF="'.SELF.'">Back</A></FONT></CENTER>';
    }
  }
  else {

  tblhead();
  ?>
<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <B>Create Forum</B></DIV>
  <?
  tblbody();
  ?>
  <FORM ACTION="<?=SELF.'action=a_createforum'?>" METHOD="post">
  <TR><TD BGCOLOR="#F8F6DE">Forum Name: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="name" SIZE="50" MAXLENGTH="50"></TD>
  </TR><TR>
  <TD COLSPAN="2" ALIGN="center"><INPUT TYPE="submit" NAME="sub" VALUE=" Create "></TD>
  </TR>
  </FORM>
  <?
  tblfoot();
 }
}

function edit_topic($id) { 
  if (isset($_POST['sub'])) {
    $err = false;
    $title = $_POST['title'];
    $author = $_POST['author'];
    $open = (int)$_POST['open'];
	$sticky = (int)$_POST['sticky'];
	
    if (strlen($title) < 1 || strlen($title) > 40) {
      echo '<CENTER><FONT COLOR="red"><B>Title must be between 1 and 40 chars</B></FONT></CENTER>';
      $err = true;
    }
    if (!is_numeric($author) && $author != '') {
      echo '<CENTER><FONT COLOR="red"><B>Author ID must be a number or nothing for anonymous</B></FONT></CENTER>';
      $err = true;
    }
    if (!$err) {
      $author = (int)$author;
      mysql_query('UPDATE twhl_forumtopics SET topic_title = "'.$title.'", topic_poster = '.$author.', topic_open = '.$open.', topic_sticky = '.$sticky.' WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
      echo '<CENTER><FONT COLOR="green"><B>Done!</B></FONT></CENTER>';
    }
  }
  
  $s_topicrs = mysql_query('SELECT forum, topic_title, forum_name, topic_poster, topic_open, topic_sticky FROM twhl_forumtopics LEFT JOIN twhl_forums ON forum = forum_id WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($s_topicrs) == 1) {
    $s_trow = mysql_fetch_array($s_topicrs);
  }
  else {
    echo 'Topic not found. <A HREF="'.SELF.'">Back</A>';
    return;
  }
 
  tblhead();
  ?>
<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <A HREF="<?=SELF.qstr('id',$s_trow['forum'],qstr('action','viewforum',''))?>"><?=$s_trow['forum_name']?></A> &raquo; <B>Edit Topic</B></DIV>
  <?
  tblbody();
  ?>
  <FORM ACTION="<?=SELF.'action=a_edittopic&id='.$_GET['id']?>" METHOD="post">
  <TR><TD BGCOLOR="#F8F6DE">Topic Title: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="title" VALUE="<?=$s_trow['topic_title']?>" SIZE="50" MAXLENGTH="40"></TD>
  </TR>
  <TR><TD BGCOLOR="#F8F6DE">Author ID: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="author" VALUE="<?=$s_trow['topic_poster']?>" SIZE="5" MAXLENGTH="10">
  Note: The topic poster is separate from the author of the first post in the topic, but they should be the same.</TD>
  </TR>
  <TR><TD BGCOLOR="#F8F6DE">Open: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="checkbox" NAME="open" VALUE="1" <?=($s_trow['topic_open'])?'checked':''?>> Untick this box to close the thread to new posts.
  </TR>
  <TR><TD BGCOLOR="#F8F6DE">Sticky: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="checkbox" NAME="sticky" VALUE="1" <?=($s_trow['topic_sticky'])?'checked':''?>> Topic is sticky.
  </TR>
  <TR><TD COLSPAN="2" ALIGN="center"><INPUT TYPE="submit" NAME="sub" VALUE=" Submit "></TD>
  </TR>
  </FORM>
  <?
  tblfoot();
}

function del_topic($id) {
  $gone = false;
  $s_topicrs = mysql_query('SELECT forum, topic_title, forum_name FROM twhl_forumtopics LEFT JOIN twhl_forums ON forum = forum_id WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($s_topicrs) == 1) {
    $s_trow = mysql_fetch_array($s_topicrs);
  }
  else {
    echo 'Topic not found. <A HREF="'.SELF.'">Back</A>';
    return;
  }
  
  if (isset($_POST['sub'])) {
    $err = false;
    if (!$err) {
      mysql_query('DELETE FROM twhl_forumtopics WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
      mysql_query('DELETE FROM twhl_forumposts WHERE topic = '.$id,$GLOBALS['dbh']) or die('Database error');
      $del = mysql_affected_rows($GLOBALS['dbh']);
      // in case the forum lastpost was in this topic, refetch the lastpost info.
      $postrs2 = mysql_query('SELECT post_id, topic, forum FROM twhl_forumposts LEFT JOIN twhl_forumtopics ON topic = topic_id WHERE forum = '.$s_trow['forum'].' ORDER BY post_time DESC LIMIT 1',$GLOBALS['dbh']) or die('Database error');
      if (mysql_num_rows($postrs2) != 1) die('Strange database error: returned ' . mysql_num_rows($postrs2) . ' results, expected 1 when getting last post in forum!');
      $postrs2 = mysql_fetch_array($postrs2);
      mysql_query('UPDATE twhl_forums SET forum_posts = forum_posts - '.$del.', forum_topics = forum_topics - 1, forum_lastpost = '.$postrs2['post_id'].' WHERE forum_id = '.$s_trow['forum'],$GLOBALS['dbh']) or die('Database error');
      echo '<CENTER><FONT COLOR="green"><B>Done!</B></FONT></CENTER>';
      $gone = true;
    }
  }
  
  tblhead();
  ?>
<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <A HREF="<?=SELF.qstr('id',$s_trow['forum'],qstr('action','viewforum',''))?>"><?=$s_trow['forum_name']?></A> &raquo; <B>Delete Topic</B></DIV>
  <?
  tblbody();
  if (!$gone) {
  ?>
  <FORM ACTION="<?=SELF.'action=a_deltopic&id='.$_GET['id']?>" METHOD="post">
  <TR><TD BGCOLOR="#F8F6DE">Topic Title: <?=$s_trow['topic_title']?></TD>
  </TR><TR>
  <TD COLSPAN="1" ALIGN="center">Are you sure? <INPUT TYPE="submit" NAME="sub" VALUE=" Yes "></TD>
  </TR>
  </FORM>
  <?
  }
  else echo 'Topic deleted';
  tblfoot();
}

function edit_post($id) {
  #If submitting changes, check everythin'.
  if (isset($_POST['sub'])) {
    $err = false;
    $body = preparebody($_POST['body']);
    $author = $_POST['author'];
    //$posttime = getztime(-$_SESSION['tz'],strtotime($_POST['posttime'])); // neg to reverse function - get GMT from zone!
    if (!is_numeric($author) && $author != '') {
      echo '<CENTER><FONT COLOR="red"><B>Author ID must be a number or nothing for anonymous</B></FONT></CENTER>';
      $err = true;
    }
    if ($posttime == -1) {
      echo '<CENTER><FONT COLOR="red"><B>Invalid date / time</B></FONT></CENTER>';
      $err = true;
    }
    if (!$err) {
      mysql_query('UPDATE twhl_forumposts SET poster = '.$author.', post_text = "'.$body.'" WHERE post_id = '.$id,$GLOBALS['dbh']) or die('Database error');
      echo '<CENTER><FONT COLOR="green"><B>Done!</B></FONT></CENTER>';
    }
  }
  
  $postrs = mysql_query('SELECT topic, poster, post_text, post_time FROM twhl_forumposts WHERE post_id = '.$id, $GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($postrs) == 1) {
    $postrs = mysql_fetch_array($postrs);
  }
  else {
    echo 'Post not found. <A HREF="'.SELF.'">Back</A>';
    return;
  }
  
  $s_topicrs = mysql_query('SELECT forum, topic_title, forum_name FROM twhl_forumtopics LEFT JOIN twhl_forums ON forum = forum_id WHERE topic_id = '.$postrs['topic'],$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($s_topicrs) == 1) {
    $s_trow = mysql_fetch_array($s_topicrs);
  }
  else {
    echo 'Topic not found. Post is either lost or deleted. <A HREF="'.SELF.'">Back</A>';
    return;
  }
  
  tblhead();
  ?>
<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <A HREF="<?=SELF.qstr('id',$s_trow['forum'],qstr('action','viewforum',''))?>"><?=$s_trow['forum_name']?></A> &raquo; <A HREF="<?=SELF.qstr('id',$postrs['topic'],qstr('action','viewthread',''))?>"><?=$s_trow['topic_title']?></A> &raquo; <B>Edit Post</B></DIV>
  <?
  tblbody();
  ?>
  <FORM ACTION="<?=SELF.'action=a_editpost&id='.$_GET['id']?>" METHOD="post">
  <TR><TD BGCOLOR="#F8F6DE">Post Text: </TD>
  <TD BGCOLOR="#F8F6DE"><TEXTAREA NAME="body" COLS="70" ROWS="10" CLASS="formtext"><?=$postrs['post_text']?></TEXTAREA></TD>
  </TR>
  <TR><TD BGCOLOR="#F8F6DE">Author ID: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="author" VALUE="<?=$postrs['poster']?>" SIZE="5" MAXLENGTH="10">
  Note: The author of the first post is separate from the author of the topic, but they should be the same.</TD>
  </TR>
  <?/* Can't do time updates without lastpost updates, and that involves more queries
  <TR><TD BGCOLOR="#F8F6DE">Time: </TD>
  <TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="posttime" VALUE="<?=date('Y-m-d H:i:s', getztime($_SESSION['tz'],$postrs['post_time']))?>" SIZE="20" MAXLENGTH="20"> (format important! yyyy-mm-dd h:m:s)
  </TR>*/?><TR>
  <TD COLSPAN="2" ALIGN="center"><INPUT TYPE="submit" NAME="sub" VALUE=" Submit "></TD>
  </TR>
  </FORM>
  <?
  tblfoot();
}

function user_edit_post($thread) {

#Note to self: this doesn't take any action against people editing their own posts in our seekrit forums, but what's the problem anyway, even if they do luck it?

	if (!(is_numeric($thread))) {
		print ("Invalid thread number.");
		return;
	}
	
	#See who owns the last post, and what its ID is.
	$lastpostquery = mysql_query("SELECT post_id, poster, post_text FROM twhl_forumposts WHERE topic = " . intval($_GET['thread']) . " ORDER BY post_id DESC LIMIT 1", $GLOBALS['dbh']);
	
	if (mysql_num_rows($lastpostquery) == 1) {
		$lastpostdetail = mysql_fetch_array($lastpostquery);
	  
		if (!($lastpostdetail['1'] == $_SESSION['uid'])) {
			
			print ("The last post in this thread is not yours!");
			return;
			
		} else {
			
			#If submitted, update!
			if ($_POST['sub']) {
				if (trim($_POST['body']) !== "") {
					
					$newbody = preparebody($_POST['body']);
					
					mysql_query("UPDATE twhl_forumposts SET post_text = '" . $newbody . "' WHERE post_id = " . $lastpostdetail[0], $GLOBALS['dbh']) or die('Database error');
					echo '<CENTER><FONT COLOR="green"><B>Done!</B></FONT></CENTER>';
					return;
					
				} else {
				
					print ("<center><font color=\"red\"><b>You must have a new message body.</b></font></center>");
					return;
				}
			} else {
			
				tblhead();
				?>
				<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <B>Edit Post</B></DIV>
				<?
				tblbody();
				?>
				<FORM ACTION="<?=SELF.'action=u_editpost&thread='.$_GET['thread']?>" METHOD="post">
				<TR>
				<TD BGCOLOR="#F8F6DE" ALIGN="center"><TEXTAREA NAME="body" COLS="70" ROWS="10" CLASS="formtext"><?=$lastpostdetail['2']?></TEXTAREA></TD>
				</TR>
				
				<?/* Can't do time updates without lastpost updates, and that involves more queries
				<TR><TD BGCOLOR="#F8F6DE">Time: </TD>
				<TD BGCOLOR="#F8F6DE"><INPUT TYPE="text" NAME="posttime" VALUE="<?=date('Y-m-d H:i:s', getztime($_SESSION['tz'],$postrs['post_time']))?>" SIZE="20" MAXLENGTH="20"> (format important! yyyy-mm-dd h:m:s)
				</TR>*/?><TR>
				<TD COLSPAN="2" ALIGN="center"><INPUT TYPE="submit" NAME="sub" VALUE=" Submit "></TD>
				</TR>
				</FORM>
				<?
				tblfoot();
			
			}
		}
		
	} else {
		print ("Thread not found, or not valid.");
		return;
	}
}

function del_post($id) {
  $postrs = mysql_query('SELECT topic, poster, post_text, post_time FROM twhl_forumposts WHERE post_id = '.$id, $GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($postrs) == 1) {
    $postrs = mysql_fetch_array($postrs);
  }
  else {
    echo 'Post not found. <A HREF="'.SELF.'">Back</A>';
    return;
  }
  
  $s_topicrs = mysql_query('SELECT forum, topic_title, topic_replies, topic_lastpost, forum_name, forum_lastpost FROM twhl_forumtopics LEFT JOIN twhl_forums ON forum = forum_id WHERE topic_id = '.$postrs['topic'],$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($s_topicrs) == 1) {
    $s_trow = mysql_fetch_array($s_topicrs);
  }
  else {
    echo 'Topic not found. Post is either lost or deleted. <A HREF="'.SELF.'">Back</A>';
    return;
  }
  
  if (isset($_POST['sub'])) {
    $err = false;
    if (!$err) {
      mysql_query('DELETE FROM twhl_forumposts WHERE post_id = '.$id,$GLOBALS['dbh']) or die('Database error');
      mysql_query('UPDATE twhl_forumtopics SET topic_replies = topic_replies - 1 WHERE topic_id = '.$postrs['topic'],$GLOBALS['dbh']) or die('Database error');
      mysql_query('UPDATE twhl_forums SET forum_posts = forum_posts - 1 WHERE forum_id = '.$s_trow['forum'],$GLOBALS['dbh']) or die('Database error');
      
	  echo '<CENTER><FONT COLOR="green"><B>Done!</B></FONT></CENTER>';
      if ($s_trow['topic_replies'] == 0) { // if there were no other replies, delete topic
	  
        mysql_query('DELETE FROM twhl_forumtopics WHERE topic_id = '.$postrs['topic'],$GLOBALS['dbh']) or die('Database error');
        mysql_query('UPDATE twhl_forums SET forum_topics = forum_topics - 1 WHERE forum_id = '.$s_trow['forum'],$GLOBALS['dbh']) or die('Database error');
        echo '<CENTER>(no other posts so topic was also deleted)</CENTER>';
      
	  } // if there are other replies, but this one was the latest, update topic & forum 'lastpost' info.
      elseif ($s_trow['topic_lastpost'] == $id || $s_trow['forum_lastpost'] == $id) {
        $postrs2 = mysql_query('SELECT post_id FROM twhl_forumposts WHERE topic = '.$postrs['topic'].' ORDER BY post_time DESC LIMIT 1',$GLOBALS['dbh']) or die('Database error');
        if (mysql_num_rows($postrs2) != 1) die('Strange database error');
        $postrs2 = mysql_fetch_array($postrs2);
        mysql_query('UPDATE twhl_forumtopics SET topic_lastpost = '.$postrs2['post_id'].' WHERE topic_id = '.$postrs['topic'],$GLOBALS['dbh']) or die('Database error');
      
	  }
	  
	  // the next post in this topic might not be the latest in the forum, so refetch the lastpost info.
	  // Moved here so that it actually runs if the sole post of a topic and the topic itself are deleted.
	  
        $postrs2 = mysql_query('SELECT post_id, forum FROM twhl_forumposts LEFT JOIN twhl_forumtopics ON topic = topic_id WHERE forum = '.$s_trow['forum'].' ORDER BY post_time DESC LIMIT 1',$GLOBALS['dbh']) or die('Database error');
        if (mysql_num_rows($postrs2) != 1) die('Strange database error: returned ' . mysql_num_rows($postrs2) . ' results, expected 1 when getting last post after deleting only post in a topic!');
        $postrs2 = mysql_fetch_array($postrs2);
        mysql_query('UPDATE twhl_forums SET forum_posts = forum_posts - 1, forum_topics = forum_topics - 1, forum_lastpost = '.$postrs2['post_id'].' WHERE forum_id = '.$s_trow['forum'],$GLOBALS['dbh']) or die('Database error');


      $gone = true;
    }
  }
  
  tblhead();
  ?>
<DIV ALIGN="center"><A HREF="<?=SELF?>">Forums</A> &raquo; <A HREF="<?=SELF.qstr('id',$s_trow['forum'],qstr('action','viewforum',''))?>"><?=$s_trow['forum_name']?></A> &raquo; <A HREF="<?=SELF.qstr('id',$postrs['topic'],qstr('action','viewthread',''))?>"><?=$s_trow['topic_title']?></A> &raquo; <B>Delete Post</B></DIV>
  <?
  tblbody();
  if (!$gone) {
  ?>
  <FORM ACTION="<?=SELF.'action=a_delpost&id='.$_GET['id']?>" METHOD="post">
  <TR><TD BGCOLOR="#F8F6DE"><?=post_format((strlen($postrs['post_text']) > 100)?substr($postrs['post_text'],0,100).'...':$postrs['post_text'])?></TD>
  </TR><TR>
  <TD COLSPAN="1" ALIGN="center">Are you sure? <INPUT TYPE="submit" NAME="sub" VALUE=" Yes "></TD>
  </TR>
  </FORM>
  <?
  }
  else echo 'Post deleted';
  tblfoot();
}
?>