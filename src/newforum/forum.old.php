<?

define('SELF',$_SERVER['PHP_SELF'].'?');
$err = '';

require('genfunc.inc.php');
require('admin.inc.php');

switch ($_SESSION['forumaccess']) {
  default:
    define('MOD_POST',false);
    define('MOD_POST2',false);
    define('MOD_ADMIN',false);
    break;  // nothing
  case 1:
    define('MOD_POST',true);
    define('MOD_POST2',false);
    define('MOD_ADMIN',false);
    break;  // can edit / delete / move posts & topics
  case 2:
    define('MOD_POST',true);
    define('MOD_POST2',true);
    define('MOD_ADMIN',false);
    break; // can post announcements / stickies
  case 3:
    define('MOD_POST',true);
    define('MOD_POST2',true);
    define('MOD_ADMIN',true);
    break; // can edit / delete forums
}

if ($_SESSION['MVAccess']) {
	define('MOD_MV',true);
} else {
	define('MOD_MV',false);
}

function tblhead() {
?>
<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" WIDTH="100%">
<TR>
<TD BGCOLOR="#edebd4">
<?
}

function tblbody() {
?>
</TD>
</TR><TR>
<TD BGCOLOR="#fefce5">
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="100%">

<?
}

function tblfoot() {
?>
</TABLE>
</TD>
</TR>
</TABLE>
<?
}

function postform($mode) {
  switch ($mode) {
    default:
      $mode = 0;
      $heading = 'Create a new topic';
      break;
    case 1:
      $mode = 1;
      $heading = 'Post a reply';
      break;
  }
?>

<SCRIPT LANGUAGE="JavaScript">
function smilie(txt) {
  el = document.forms['create'].body;
  el.value += ((el.value.charAt(el.value.length-1) == ' ')?'':' ')+txt+' '; 
  el.focus();
}

function checkPost() {
  el = document.forms['create'];
  if (el.body.value == '') {
    alert('Cannot submit empty posts!');
    return false;
  }
  if (el.subject) {
    if (el.subject.value == '') {
      alert('You need to provide a subject');
      return false;
    }
  }
}
</SCRIPT>


<FORM NAME="create" ACTION="<?=SELF.qstr('action',($mode == 1)?'createreply':'createtopic','')?>" METHOD="post" onSubmit="return checkPost()">
<?=tblhead()?>
<DIV ALIGN="center"><B><?=$heading?></B></DIV>
<?=tblbody()?>
<TR>
<?if (!isset($_SESSION['uid'])) {?>
<TD BGCOLOR="#F1EFD7" COLSPAN="4" ALIGN="center">
You need to log in before you post.<BR>
If you don't have an account, <A HREF="signup.php?return=<?=SELF.$_SERVER['QUERY_STRING']?>">get one here</A>.
<?} else {?>
<?
	if ($mode == 0) {

	if ($_GET['id'] == 4 && isset ($_SESSION['forumHelp']) && $_SESSION['forumHelp'] && $_SESSION['loggedint'] < 30) {
		print ("<td colspan=\"4\" style=\"color: #dd0000; font-weight: bold; text-align: center\">Do not post mapping questions in the \"Maps and Mods\" forum, as they will be deleted! Read the forum descriptions on the forum index before posting!</td></tr><tr>");
	}

?>

<TD BGCOLOR="#F1EFD7" ALIGN="right">
	Subject: <INPUT TYPE="text" NAME="subject" SIZE="30" MAXLENGTH="40" CLASS="formtext">
	
	<?php
		if (MOD_POST) { ?>
			<br><span style="font-size: 10px">Is sticky <input type="checkbox" name="issticky"> || Is closed <input type="checkbox" name="isclosed"></span>
		<?php }
	?>
</TD>
<?} else {?>
<TD BGCOLOR="#F1EFD7"><IMG SRC="../gfx/dot_trans.gif" WIDTH="230" HEIGHT="1"></TD>
<?}?>
<TD BGCOLOR="#F1EFD7" ALIGN="right"><INPUT TYPE="submit" NAME="sub" VALUE="Post"><!--&nbsp;&nbsp;<INPUT TYPE="button" VALUE="Preview" onClick="this.form.action='<?=addslashes(SELF.qstr('action','preview',''))?>'">--></TD>
<TD BGCOLOR="#F1EFD7" ALIGN="right">
  <TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2"><TR><TD>
  <A HREF="JavaScript:smilie(':)')"><IMG SRC="newforum/smilies/smile.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':|')"><IMG SRC="newforum/smilies/frown.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':(')"><IMG SRC="newforum/smilies/sad.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':x')"><IMG SRC="newforum/smilies/angry.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':lol:')"><IMG SRC="newforum/smilies/lol.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':confused:')"><IMG SRC="newforum/smilies/confused.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':o')"><IMG SRC="newforum/smilies/surprised.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':roll:')"><IMG SRC="newforum/smilies/rollseyes.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':P')"><IMG SRC="newforum/smilies/tongue.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(';)')"><IMG SRC="newforum/smilies/wink.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  </TD><TD ROWSPAN="2"><B><A HREF="forumhelp.php">&nbsp;[?]</A></B></TD>
  </TR><TR><TD>

  <A HREF="JavaScript:smilie(':D')"><IMG SRC="newforum/smilies/grin.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':\\')"><IMG SRC="newforum/smilies/unsure.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':zonked:')"><IMG SRC="newforum/smilies/zonked.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':nuts:')"><IMG SRC="newforum/smilies/nuts.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':badass:')"><IMG SRC="newforum/smilies/badass.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':cool:')"><IMG SRC="newforum/smilies/toocool.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':aggrieved:')"><IMG SRC="newforum/smilies/aggrieved.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':aghast:')"><IMG SRC="newforum/smilies/aghast.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':cry:')"><IMG SRC="newforum/smilies/cry.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':cyclops:')"><IMG SRC="newforum/smilies/cyclops.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  </TD></TR><TR><TD>
  
  <A HREF="JavaScript:smilie(':furious:')"><IMG SRC="newforum/smilies/furious.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':glad:')"><IMG SRC="newforum/smilies/glad.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':heart:')"><IMG SRC="newforum/smilies/heart.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':nervous:')"><IMG SRC="newforum/smilies/nervous.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':nuke:')"><IMG SRC="newforum/smilies/nuke.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':quizzical:')"><IMG SRC="newforum/smilies/quizzical.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':sarcastic:')"><IMG SRC="newforum/smilies/sarcastic.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':thefinger:')"><IMG SRC="newforum/smilies/thefinger.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':tired:')"><IMG SRC="newforum/smilies/tired.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  <A HREF="JavaScript:smilie(':biggrin:')"><IMG SRC="newforum/smilies/biggrin.gif" WIDTH="15" HEIGHT="15" BORDER="0"></A>
  </TR></TD></TABLE>
</TD>
<TD BGCOLOR="#F1EFD7" ALIGN="right"><!--Help!--></TD>
</TR>
<TR><TD BGCOLOR="#F1EFD7" COLSPAN="4" ALIGN="center"><TEXTAREA NAME="body" COLS="70" ROWS="10" CLASS="formtext"></TEXTAREA></TD></TR>

<?

if (MOD_POST) {
	print ("<TR><TD BGCOLOR=\"#F1EFD7\" COLSPAN=\"4\" ALIGN=\"center\">&nbsp;</TD></TR>");
	print ("<tr><td BGCOLOR=\"#F1EFD7\" COLSPAN=\"4\" ALIGN=\"center\"><a href=\"thread_toggleclose.php?threadid=" . $_GET['id'] . "\">Close thread</td></tr>");
	print ("<TR><TD BGCOLOR=\"#F1EFD7\" COLSPAN=\"4\" ALIGN=\"center\">&nbsp;</TD></TR>");
	}

}
?>

</TD></TR>
<?=tblfoot()?>
</FORM>
<?
}

function setpages($page,$number) {
  switch ($page) {
    default: // threads
      
	  $ppp = 20;
      
	  if ($_SESSION['bigScreen']) {
		$ppp = 50;
	  }
	  
	  $pgact = 'viewthread';
      $pq = 'pg';break;
	  
    case 'topics':
	
      $ppp = 30;
      
	  if ($_SESSION['bigScreen']) {
		$ppp = 60;
	  }
	  
      $pgact = 'viewforum';
      $pq = 'pgt';break;
	  
    case 'search':
	
      $ppp = 30;
      
	  if ($_SESSION['bigScreen']) {
		$ppp = 60;
	  }
	  
      $pgact = 'search';
      $pq = 'pgt';break;
  }
  $pg = $_GET[$pq];
  if ($pg == 'last') $pg = ceil($number / $ppp);
  if (!is_numeric($pg) || $pg < 1) $pg = 1;
  if (($pg-1)*$ppp > $number) $pg = 1;

  $GLOBALS['number'] = $number; #No. of items paginated? Yeah, that'd make sense.
  $GLOBALS['ppp'] = $ppp; #Posts per page?
  $GLOBALS['pq'] = $pq; #Ya nye znayu. Maybe this was intended to get different things to use different variable names to set the page.
  $GLOBALS['pg'] = $pg; #Present page?
  $GLOBALS['pgact'] = $pgact; #Action to continue to do when moving pages
}

function getpgoffset($type) {
  // returns result offset for current page.
  // must have SETPAGES called first!
  $ppp = $GLOBALS['ppp'];
  $pg = $GLOBALS['pg'];
  if ($type) return ($pg-1)*$ppp;
  else return $ppp;
}

function pagelinks() {
  // prints page numbers
  // must have SETPAGES called first!
  $ppp = $GLOBALS['ppp'];
  $pg = $GLOBALS['pg'];
  $pq = $GLOBALS['pq'];
  $pgact = $GLOBALS['pgact'];
  $number = $GLOBALS['number'];
  $pages = ceil($number / $ppp);
  echo 'Page ';
  //for($i=1;$i<=$pages;$i++) {
  //  if ($i == $pg) echo '['.$i.'] ';
  //  else echo '<A HREF="'.SELF.qstr($pq,$i,qstr('action',$pgact,'')).'">'.$i.'</A> ';
  //}

  echo linklist($pages, $pg, 15, '<A HREF="'.SELF.qstr($pq,'%d',qstr('action',$pgact,'')).'">%d</A>');


  //echo ' of '.$pages;
}

function getuname($uname) {
    if ($uname != '') {
      return '<A HREF="userprofile.php?user='.$uname.'">'.$uname.'</A>';
    }
    else return 'Anonymous';
}


//---------------------------------------- MAIN FUNCTIONS BEGIN HERE --

function fmain() {
  $action = $_GET['action'];
  $id = $_GET['id'];
  if (!is_numeric($id)) $id = 0; // if there's no id
  
  switch ($action) {
    default:
      forum_list();break;
    case 'search':
      search_forum();break;
    case 'viewforum':
      show_forum($id);break;
    case 'viewthread':
      show_thread($id);break;
    case 'createtopic':
      create_topic($id);break;
    case 'createreply':
      create_reply($id);break;
    case 'preview':
      preview_post();break;
    case 'a_createforum':
      if (MOD_ADMIN) create_forum();
      else forum_list();
      break;
    case 'a_edittopic':
      if (MOD_POST) edit_topic($id);
      else forum_list();
      break;
    case 'a_deltopic':
      if (MOD_POST) del_topic($id);
      else forum_list();
      break;
    case 'a_editpost':
      if (MOD_POST) edit_post($id);
      else forum_list();
      break;
	case 'u_editpost':
	  user_edit_post($_GET['thread']);
	  break;
	  
    case 'a_delpost':
      if (MOD_POST) del_post($id);
      else forum_list();
      break;
  }
}

// ---- LIST ALL FORUMS

function forum_list() {
?>
<?=tblhead()?>
<?

if (MOD_POST) {
$forumtotal = mysql_query('SELECT COUNT(*) FROM twhl_forums',$GLOBALS['dbh']) or die('Database error');
} elseif ($_SESSION['mvaccess'] > 0) {
$forumtotal = mysql_query('SELECT COUNT(*) FROM twhl_forums WHERE for_forummods<>1',$GLOBALS['dbh']) or die('Database error');
} else {
$forumtotal = mysql_query('SELECT COUNT(*) FROM twhl_forums WHERE for_forummods<>1 AND for_mvmods<>1',$GLOBALS['dbh']) or die('Database error');
}

if (mysql_num_rows($forumtotal) == 1) {
  $forumtotal = mysql_fetch_array($forumtotal);
  $forumtotal = $forumtotal[0];
}
else $forumtotal = 0; // never
?>
<DIV ALIGN="center"><B>Forums</B> <?=(MOD_ADMIN)?'[<A HREF="'.SELF.'action=a_createforum">create</A>]':''?></DIV>
<DIV ALIGN="right">Forums: <?=$forumtotal?> | Moderators: 
<?
$rs_mods = mysql_query("SELECT UserID, Username FROM twhl_users WHERE ForumAccess > 0 ORDER BY UserName ASC",$GLOBALS['dbh']) or die('Database error');
$num_rows = mysql_num_rows($rs_mods);
if($num_rows>0) {
  while ($row = mysql_fetch_array($rs_mods)) {
    if ($notfirst) {
      echo ", ";
    }
    echo '<A HREF="userprofile.php?id='.$row["UserID"].'">'.$row["Username"]."</A>";
    $notfirst = true;
  }
}
else {
  echo "There aren't any moderators";
}
?>
</DIV>
<?=tblbody()?>
<TR><TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="left">FORUM NAME</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">TOPICS</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">POSTS</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="right">LAST POST</TD></TR>

<?
// Complex query. Could be time-consuming. Gets forum info, gets last past info, gets user info.
$forumrs = mysql_query("SELECT forum_id, forum_name, forumDescription, forum_topics, forum_posts, forum_lastpost, for_forummods, for_mvmods, poster, topic, topic_title, post_time, Username FROM twhl_forums LEFT JOIN twhl_forumposts ON forum_lastpost = post_id LEFT JOIN twhl_forumtopics ON topic = topic_id LEFT JOIN twhl_users ON poster = UserID ORDER BY twhl_forums.orderindex ASC",$GLOBALS['dbh']) or die("Database error");
if (mysql_num_rows($forumrs) > 0) {
  $cnt = false;
  while ($frow = mysql_fetch_array($forumrs)) {
    if (!$cnt) $cnt = true;
    else $cnt = false;
    $topictitle = $frow['topic_title'];
    if (strlen($topictitle) > 15) {
      $topictitle = trim(substr($topictitle,0,12)).'...';
    }
    $newpost = false;
    if ($_SESSION['uid'] && $frow['post_time'] > strtotime($_SESSION['lastlogin'])) {
      $newpost = true;
    }
	
	//Are they a forum mod? Show them any extra forums; otherwise, just list the public ones.
	
	if (
	($frow["for_forummods"] && MOD_POST) ||
	($frow["for_mvmods"] && $_SESSION['mvaccess']) ||
	(!($frow["for_forummods"]) && !($frow["for_mvmods"]))
	)	{
	
?>
<TR><TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?>>

<?

if ($newpost) {
	print ("<img src=\"gfx/forum_new.gif\" alt=\"New post indicator\" height=\"8\" width=\"8\">");
} else {
	print ("<img src=\"gfx/forum_normal.gif\" alt=\"Normal forum indicator\" height=\"8\" width=\"8\">");
}

?>

<A HREF="<?=SELF.qstr('id',$frow['forum_id'],qstr('action','viewforum',qstr('pgt','1','')))?>"><?=$frow['forum_name']?></A>

<?php

if (!($_SESSION['forumHelp'] == 0 && $_SESSION['uid'])) {

	print ("<br><span style=\"font-size: 7pt; color: #888888\">" . $frow['forumDescription'] . "</span>");
	
}


?>

</TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$frow['forum_topics']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$frow['forum_posts']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="right" STYLE="font-size: 7pt;"><?if ($frow['forum_lastpost'] > 0) {?><?=date('d M y H:i',getztime($_SESSION['tz'],$frow['post_time']))?><BR>In <A HREF="<?=SELF.qstr('id',$frow['topic'],qstr('action','viewthread',qstr('pg','last','')))?>"><?=$topictitle?></A>, by <?=getuname($frow['Username'])?> <?} else {?>Never<?}?></TD></TR>
<?

  }
  }
}
else {
  ?>
<TR><TD BGCOLOR="#F8F6DE" COLSPAN="4" ALIGN="center"><I>No forums available</I></TD></TR>
  <?
}
?>

<?=tblfoot()?>

<br><br><br>

<?

	#Display the forum indicator key?
	if (!($_SESSION['forumHelp'] == 0 && $_SESSION['uid'])) {
		include ("forumkey.php");
	}
}

// ---- SHOW TOPICS IN ONE FORUM

function show_forum($id) {

$s_forumrs = mysql_query("SELECT forum_name, forum_topics, for_forummods, for_mvmods FROM twhl_forums WHERE forum_id = " . $id, $GLOBALS['dbh']) or die('Database error');

if (mysql_num_rows($s_forumrs) == 1) {

	$s_frow = mysql_fetch_array($s_forumrs);
	
	//If they're trying to view a private forum and aren't a mod, deny it. Visibly.
	if (
	($s_frow['for_forummods'] && !MOD_POST) ||
	($s_frow['for_mvmods'] && !$_SESSION['mvaccess'] > 0)
	
	) {
		print ("You do not have access to this forum.");
		return;
	}
}
else {
  echo 'Forum not found. <A HREF="'.SELF.'">Back</A>';
  return;
}
?>
<?setpages('topics',$s_frow['forum_topics'])?>
<?=tblhead()?>
<DIV ALIGN="center"><A HREF="<?=SELF.qstr('action','','')?>">Forums</A> &raquo; <B><?=$s_frow['forum_name']?></B></DIV>
<DIV ALIGN="right"><?=pagelinks()?> | Topics: <?=$s_frow['forum_topics']?> | <A HREF="#newtopic">New topic</A></DIV>
<?=tblbody()?>
<TR><TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="left">TOPIC</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">REPLIES</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">VIEWS</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">AUTHOR</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="right">LAST POST</TD>
<?=(MOD_POST)?'<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="right">MOD STUFF</TD>':''?>
</TR>

<?

// EAT THAT!

// REGULAR THREADS NOW
// Gets topic info, links to users for username of topic author and for username of last post.
$topicrs = mysql_query('SELECT topic_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_open, topic_sticky, post_time, twhl_users.Username, twhl_users2.Username AS lastpost_uname FROM twhl_forumtopics LEFT JOIN twhl_forumposts ON topic_lastpost = post_id LEFT JOIN twhl_users ON topic_poster = twhl_users.UserID LEFT JOIN twhl_users AS twhl_users2 ON poster = twhl_users2.UserID WHERE forum = '.$id.' ORDER BY topic_sticky DESC, post_time DESC LIMIT '.getpgoffset(1).','.getpgoffset(0),$GLOBALS['dbh']) or die('Database error');
if (mysql_num_rows($topicrs) > 0) { 
  $cnt = false;
  while ($trow = mysql_fetch_array($topicrs)) {
    if (!$cnt) $cnt = true;
    else $cnt = false;
    $newpost = false;
	$lockedtopic = false;
	$sticky = false;
	
    if ($_SESSION['uid'] && $trow['post_time'] > strtotime($_SESSION['lastlogin'])) {
		$newpost = true;
    }
	
	if (!$trow['topic_open']) {
		$lockedtopic = true;
	}

	if ($trow['topic_sticky']) {
		$sticky = true;
	}
	
    ?>
<TR><TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?>>

<?

if ($sticky) {

	if ($lockedtopic) {
		print ("<img src=\"gfx/forum_sticky_locked.gif\" alt=\"Thread locked indicator\" height=\"8\" width=\"8\">");
	} elseif ($newpost) {
		print ("<img src=\"gfx/forum_sticky_new.gif\" alt=\"New post indicator\" height=\"8\" width=\"8\">");
	} else {
		print ("<img src=\"gfx/forum_sticky_normal.gif\" alt=\"Normal thread indicator\" height=\"8\" width=\"8\">");
	}

} else {
	
	if ($lockedtopic) {
		print ("<img src=\"gfx/forum_locked.gif\" alt=\"Thread locked indicator\" height=\"8\" width=\"8\">");
	} elseif ($newpost) {
		print ("<img src=\"gfx/forum_new.gif\" alt=\"New post indicator\" height=\"8\" width=\"8\">");
	} else {
		print ("<img src=\"gfx/forum_normal.gif\" alt=\"Normal thread indicator\" height=\"8\" width=\"8\">");
	}

}	
?>

<A HREF="<?=SELF.qstr('id',$trow['topic_id'],qstr('action','viewthread',qstr('pg','1','')))?>">
<?=$trow['topic_title']?></A></TD>

<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$trow['topic_replies']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$trow['topic_views']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=getuname($trow['Username'])?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="right" STYLE="font-size: 7pt;"><?=date('d M y H:i',getztime($_SESSION['tz'],$trow['post_time']))?><BR>by <?=getuname($trow['lastpost_uname'])?> <A HREF="<?=SELF.qstr('id',$trow['topic_id'],qstr('action','viewthread',qstr('pg','last','')))?>">&raquo;</A></TD>
<?=(MOD_POST)?'<TD '.(($cnt)?'BGCOLOR="#F8F6DE"':'').' STYLE="font-size: 7pt;" ALIGN="right">[<A HREF="'.SELF.'action=a_edittopic&id='.$trow['topic_id'].'">E</A>] [<A HREF="thread_move.php?threadid='.$trow['topic_id'].'">M</A>] [<A HREF="'.SELF.'action=a_deltopic&id='.$trow['topic_id'].'">D</A>]</TD>':''?>
</TR>
    <?
  }
}
else {

  ?>
<TR><TD BGCOLOR="#F8F6DE" COLSPAN="6" ALIGN="center"><I>No topics in this forum</I></TD></TR>
  <?
}
?>
</TABLE>
</TD></TR>
<TR><TD BGCOLOR="#edebd4">
<DIV ALIGN="right"><?=pagelinks()?> | Topics: <?=$s_frow['forum_topics']?> | <A HREF="#newtopic">New topic</A></DIV>
</TD></TR>
</TABLE>
<P>
<A NAME="#newtopic"></A>
<?=postform(0)?>
<?
}

// ---- SEARCH POSTS AND TOPICS AND DISPLAY AS TOPIC LIST

function search_forum() {
$srchstr = sqlquote($_GET['str']);
$srchtype = $_GET['type'];
if ($srchstr != '') {
	
	if (strlen($srchstr) >= 4) {
	  // currently only searches name and bio
	  $strsplit = explode(' ',$srchstr);
	  for($i=0;$i<count($strsplit);$i++) {
		if ($srchtype == 0) {	// 0 = Any words, 1 = All words NOTE: automatically a substring search  This IF could be neater
		  if ($i != 0) $strjoin .= " OR ";$strjoin .= "topic_title LIKE '%".$strsplit[$i]."%' OR twhl_forumposts.post_text LIKE '%".$strsplit[$i]."%'";
		}
		else {
		  if ($i != 0) $strjoin .= " AND ";$strjoin .= "(topic_title LIKE '%".$strsplit[$i]."%' OR twhl_forumposts.post_text LIKE '%".$strsplit[$i]."%')";
		}
	  }
	  $strjoin = '('.$strjoin.')';
	} else {
	print ("Search term must be at least four characters long. <A HREF=\"" . SELF . "\">Back</A>");
	return;
	}
} else {
  echo 'Enter a search query. <A HREF="'.SELF.'">Back</A>';
  return;
}

// search is a bit inefficient because it will return every post from a topic if it's just the 
// topic that matched. While this is logical under normal circumstances, this forum system isn't 
// supposed to treat each post as having its own title.
// why doesn't COUNT(*) work here?!?!?!?! It should replace this method, because it's more efficient.

if (MOD_POST) {
	$stotal = mysql_query('SELECT topic_id FROM twhl_forumtopics LEFT JOIN twhl_forumposts ON topic_id = topic WHERE '.$strjoin.' GROUP BY topic_id',$GLOBALS['dbh']) or die('Database error');
} else {
	$stotal = mysql_query('SELECT topic_id, for_forummods FROM twhl_forumtopics LEFT JOIN twhl_forumposts ON topic_id = topic LEFT JOIN twhl_forums ON forum = forum_id WHERE for_forummods <> 1 AND '.$strjoin.' GROUP BY topic_id',$GLOBALS['dbh']) or die('Database error');
}

//$stotal = mysql_fetch_array($stotal);
//$stotal = $stotal[0];
$stotal = mysql_num_rows($stotal);
?>
<?setpages('search',$stotal)?>
<?=tblhead()?>
<DIV ALIGN="center"><A HREF="<?=SELF.qstr('action','','')?>">Forums</A> &raquo; <B>Search</B></DIV>
<DIV ALIGN="right"><?=pagelinks()?> | Topics found: <?=$stotal?></DIV>
<?=tblbody()?>
<TR><TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="left">TOPIC</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">FORUM</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">REPLIES</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">VIEWS</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">AUTHOR</TD>
<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="center">LAST POST</TD>
<?=(MOD_POST)?'<TD BGCOLOR="#F1EFD7" STYLE="font-size: 8pt;" ALIGN="right">MOD STUFF</TD>':''?>
</TR>

<?
// this is insanely cool! It could be adapted to give the time of last post THAT MATCHED, etc.

if (MOD_POST) {
	$topicrs = mysql_query('SELECT topic_id, topic_title, topic_poster, topic_time, topic_views, 
	topic_replies, twhl_users.Username, forum, forum_name, twhl_forumposts2.post_time, 
	twhl_users2.Username AS lastpost_uname FROM twhl_forumtopics 
	LEFT JOIN twhl_forumposts ON topic_id = twhl_forumposts.topic 
	LEFT JOIN twhl_users ON topic_poster = twhl_users.UserID 
	LEFT JOIN twhl_forums ON forum = forum_id 
	LEFT JOIN twhl_forumposts AS twhl_forumposts2 ON topic_lastpost = twhl_forumposts2.post_id 
	LEFT JOIN twhl_users AS twhl_users2 ON twhl_forumposts2.poster = twhl_users2.UserID 
	WHERE '.$strjoin.' GROUP BY topic_id ORDER BY post_time DESC LIMIT '.getpgoffset(1).','.getpgoffset(0),$GLOBALS['dbh']) or die('Database error');
} else {
	$topicrs = mysql_query('SELECT topic_id, topic_title, topic_poster, topic_time, topic_views, 
	topic_replies, twhl_users.Username, forum, forum_name, twhl_forumposts2.post_time, 
	twhl_users2.Username AS lastpost_uname FROM twhl_forumtopics 
	LEFT JOIN twhl_forumposts ON topic_id = twhl_forumposts.topic 
	LEFT JOIN twhl_users ON topic_poster = twhl_users.UserID 
	LEFT JOIN twhl_forums ON forum = forum_id 
	LEFT JOIN twhl_forumposts AS twhl_forumposts2 ON topic_lastpost = twhl_forumposts2.post_id 
	LEFT JOIN twhl_users AS twhl_users2 ON twhl_forumposts2.poster = twhl_users2.UserID 
	WHERE for_forummods <> 1 AND '.$strjoin.' GROUP BY topic_id ORDER BY post_time DESC LIMIT '.getpgoffset(1).','.getpgoffset(0),$GLOBALS['dbh']) or die('Database error');
}	
	
//$topicrs = mysql_query('SELECT topic_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, post_time, twhl_users.Username, twhl_users2.Username AS lastpost_uname FROM twhl_forumtopics LEFT JOIN twhl_forumposts ON topic_lastpost = post_id LEFT JOIN twhl_users ON topic_poster = twhl_users.UserID LEFT JOIN twhl_users AS twhl_users2 ON poster = twhl_users2.UserID WHERE '.$strjoin.' ORDER BY post_time DESC LIMIT '.getpgoffset(1).','.getpgoffset(0),$GLOBALS['dbh']) or die('Database error');
if (mysql_num_rows($topicrs) > 0) { 
  $cnt = false;
  while ($trow = mysql_fetch_array($topicrs)) {
    if (!$cnt) $cnt = true;
    else $cnt = false;
    $newpost = false;
    if ($_SESSION['uid'] && $trow['post_time'] > strtotime($_SESSION['lastlogin'])) {
      $newpost = true;
    }
    ?>
<TR><TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?>><?=($newpost)?'<FONT COLOR="red"><B>*</B></FONT> ':''?><A HREF="<?=SELF.qstr('id',$trow['topic_id'],qstr('action','viewthread',qstr('pg','1','')))?>"><?=$trow['topic_title']?></A></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><A HREF="<?=SELF?>action=viewforum&id=<?=$trow['forum']?>"><?=(strlen($trow['forum_name']) > 15)?substr($trow['forum_name'],0,15).'...':$trow['forum_name']?></A></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$trow['topic_replies']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=$trow['topic_views']?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="center" STYLE="font-size: 8pt;"><?=getuname($trow['Username'])?></TD>
<TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> ALIGN="right" STYLE="font-size: 7pt;"><?=date('d M y H:i',getztime($_SESSION['tz'],$trow['post_time']))?><BR>by <?=getuname($trow['lastpost_uname'])?> <A HREF="<?=SELF.qstr('id',$trow['topic_id'],qstr('action','viewthread',qstr('pg','last','')))?>">&raquo;</A></TD>
<?=(MOD_POST)?'<TD '.(($cnt)?'BGCOLOR="#F8F6DE"':'').' STYLE="font-size: 7pt;" ALIGN="right">[<A HREF="'.SELF.'action=a_edittopic&id='.$trow['topic_id'].'">E</A>] [<A HREF="'.SELF.'action=a_deltopic&id='.$trow['topic_id'].'">D</A>]</TD>':''?>
</TR>
    <?
  }
}
else {
  ?>
<TR><TD BGCOLOR="#F8F6DE" COLSPAN="7" ALIGN="center"><I>No posts found in search</I></TD></TR>
  <?
}
?>
</TABLE>
</TD></TR>
<TR><TD BGCOLOR="#edebd4">
<DIV ALIGN="right"><?=pagelinks()?> | Topics found: <?=$stotal?></DIV>
</TD></TR>
</TABLE>
<?
}


// ---- SHOW POSTS IN ONE THREAD

function show_thread($id) {

$s_topicrs = mysql_query('SELECT forum, topic_title, forum_name, for_forummods, for_mvmods, topic_replies, topic_open FROM twhl_forumtopics LEFT JOIN twhl_forums ON forum = forum_id WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
if (mysql_num_rows($s_topicrs) == 1) {

	$s_trow = mysql_fetch_array($s_topicrs);

	//Thread in a private forum and you ain't a mod? Forget it, mate. I hope.
	if (
	($s_trow['for_forummods'] && !MOD_POST) ||
	($s_trow['for_mvmods'] && !$_SESSION['mvaccess'] > 0)
	
	) {
		print ("You do not have access to this thread.");
		return;
	}

}
else {
  echo 'Topic not found. <A HREF="'.SELF.'">Back</A>';
  return;
}
mysql_query('UPDATE twhl_forumtopics SET topic_views = topic_views + 1 WHERE topic_id = '.$id,$GLOBALS['dbh']);
?>
<?setpages('threads',$s_trow['topic_replies']+1)?>
<?=tblhead()?>
<DIV ALIGN="center"><A HREF="<?=SELF.qstr('action','','')?>">Forums</A> &raquo; <A HREF="<?=SELF.qstr('id',$s_trow['forum'],qstr('action','viewforum',''))?>"><?=$s_trow['forum_name']?></A> &raquo; <B><?=$s_trow['topic_title']?></B></DIV>
<DIV ALIGN="right"><?=pagelinks()?> | Replies: <?=$s_trow['topic_replies']?> | <A HREF="#reply">Reply</A></DIV>
<?=tblbody()?>

<?
$postrs = mysql_query('SELECT post_id, poster, post_text, post_time, Username, Avatar, ForumAccess, ForumPosts FROM twhl_forumposts LEFT JOIN twhl_users ON poster = UserID WHERE topic = '.$id.' ORDER BY post_time ASC LIMIT '.getpgoffset(1).','.getpgoffset(0),$GLOBALS['dbh']) or die('Database error');
if (mysql_num_rows($postrs) > 0) {
  $cnt = false;
  $first = true;

  #See who owns the last post, and what its ID is. If it's <user>'s, offer them the link to edit it.
  $lastpostquery = mysql_query("SELECT post_id, poster FROM twhl_forumposts WHERE topic = " . $id . " ORDER BY post_id DESC LIMIT 1", $GLOBALS['dbh']);

  #No topics should ever have no posts, so go straight on and get the result.
  $lastpostdetail = mysql_fetch_array($lastpostquery);
  
  while ($prow = mysql_fetch_array($postrs)) {
    
	#Ah-ha, this must be to alternate the backgrounds.
	if (!$cnt) $cnt = true;
    else $cnt = false;
    ?>

<TR><TD <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> VALIGN="top">
<?=(!$first)?'<HR COLOR="#cbc9b2" NOSHADE WIDTH="100%" SIZE="1">':''?>
<?

if ($prow['ForumAccess'] > 0) {
	$moderatorPost = TRUE;
} else {
	$moderatorPost = FALSE;
}

print (post_format($prow['post_text'], FALSE, $moderatorPost));

?>
</TD><TD BGCOLOR="#F1EFD7" WIDTH="1"></TD>

<TD WIDTH="180" <?=($cnt)?'BGCOLOR="#F8F6DE"':''?> STYLE="font-size: 8pt;" VALIGN="top" ALIGN="center">
<?=(!$first)?'<HR COLOR="#cbc9b2" NOSHADE WIDTH="100%" SIZE="1">':''?>
<span style="color: #888888">Posted on</span> <?=date('d M y, H:i',getztime($_SESSION['tz'],$prow['post_time']))?><BR>
<span style="color: #888888">by</span> <?=getuname($prow['Username'])?> 

<?

if (MOD_POST) {
	print ("[<A HREF=\"" . SELF . "action=a_editpost&id=" . $prow['post_id'] . "\">E</A>]
	[<A HREF=\"" . SELF . "action=a_delpost&id=" . $prow['post_id'] . "\">D</A>]");
} elseif ($prow['post_id'] == $lastpostdetail['0'] && $prow['poster'] == $_SESSION['uid']) {
	print ("[<A HREF=\"" . SELF . "action=u_editpost&thread=" . $id . "\">edit</A>]");
}

#Use li'l avatars if the user has "big screen" turned off.

$avatarPathSuffix = "_small";

if ($_SESSION['bigScreen']) {
	$avatarPathSuffix = "";
}

if (!isset($_SESSION['forumimages']) || $_SESSION['forumimages']) { ?>
	<DIV ALIGN="center">
	<?if ($prow["Avatar"] > 1) { ?>
		<IMG SRC="gfx/avatars/<?=str_pad($prow["Avatar"],3,"0",STR_PAD_LEFT)?>.gif" ALT="Avatar">
	<?}elseif($prow["Avatar"] == -1) {?>
		<IMG SRC="avatars/<?php print ($prow['poster'] . $avatarPathSuffix) ?>.jpg" ALT="Avatar">
	<?}elseif($prow["Avatar"] == -2) {?>
		<IMG SRC="avatars/<?php print ($prow['poster'] . $avatarPathSuffix) ?>.png" ALT="Avatar">
	<?}?>
	</DIV>
<?}

if ($userTitle = userTitle($prow['poster'])) {
	print ("<div align=\"center\"><span style=\"font-size: 8px;\"><br />" . $userTitle . "</span></div>");
}

?>
</TD></TR>

    <?
    $first = false;
  }
}
else {
  ?>
<TR><TD BGCOLOR="#F8F6DE" COLSPAN="4" ALIGN="center"><I>No posts found (invalid topic)</I></TD></TR>
  <?
}
?>
</TABLE>
</TD></TR>
<TR><TD BGCOLOR="#edebd4">
<DIV ALIGN="right"><?=pagelinks()?> | Replies: <?=$s_trow['topic_replies']?> | <A HREF="#reply">Reply</A></DIV>
</TD></TR>
</TABLE>
<P>
<A NAME="#reply"></A>
<?php 
if ($s_trow['topic_open']) {
	echo postform(1);
} else { 
	echo '<center><i>This topic has been closed by a moderator</i></center>';
	
	if (MOD_POST) {
	print ("<br><a href=\"thread_toggleclose.php?threadid=" . $id . "\"><center>Open thread</center></a>");
	}

}
	
?>

<?
}

// ---- POST FUNCTIONS

function create_topic($id) {

  if (!isset($_SESSION['uid'])) {
    ?>
    <FONT COLOR="red"><b>You must be logged in to use the forums.</b></FONT><P>
    <?
    return;
  }
  
  // id is forum

  $subject = preparebody(substr($_POST['subject'],0,40));
  $body = preparebody($_POST['body']);
  $user = (isset($_SESSION['uid']))?$_SESSION['uid']:0;
  $ttime = getztime();
  
  if ($body == '') {
    ?>
  <SCRIPT LANGUAGE="JavaScript">
    window.setTimeout('window.location="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>"',3000);
  </SCRIPT>
  <DIV ALIGN="center">
  <FONT COLOR="red"><B>Cannot submit empty posts!</B></FONT>
  <P>
  Click <A HREF="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>">here</A> to go back<BR>
  </DIV>
    <?
    return;
  }
  
  if ($subject == '') {
    ?>
  <SCRIPT LANGUAGE="JavaScript">
    window.setTimeout('window.location="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>"',3000);
  </SCRIPT>
  <DIV ALIGN="center">
  <FONT COLOR="red"><B>You need to provide a subject!</B></FONT>
  <P>
  Click <A HREF="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>">here</A> to go back<BR>
  </DIV>
    <?
    return;
  }
  
  // check forum exists!
  $ptopicrs = mysql_query('SELECT forum_id FROM twhl_forums WHERE forum_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($ptopicrs) != 1) die('Forum not found. <A HREF="'.SELF.'">Back</A>');
  
  $issticky = 0;
  $isopen = 1;
  
	if (MOD_POST) {
		
		#Post as sticky?
		if ($_POST['issticky']) {
			$issticky = 1;
		} 
		
		#Post as closed, announcement-style?
		if ($_POST['isclosed']) {
			$isopen = 0;
		}
		
	}

  	$lasttopicquery = mysql_query("SELECT topic_title FROM twhl_forumtopics WHERE forum = " . $id . " ORDER BY topic_id DESC LIMIT 1", $GLOBALS['dbh']) or die ("Curses!");
	$lasttopic = mysql_result ($lasttopicquery, 0);
	
	if ($lasttopic == $subject) {
		print ("<FONT COLOR=\"red\"><b>Topic title was identical to latest topic! Duplicate not posted.</b></FONT><P><a href=\"forums.php?action=viewforum&id=" . $id ."\">Back</a>");
		return;
	}

	
	mysql_query('INSERT INTO twhl_forumtopics (forum, topic_title, topic_poster, topic_time, topic_open, topic_sticky) VALUES ('.$id.', "'.$subject.'", '.$user.', '.$ttime.", " . $isopen . ", " . $issticky . ")", $GLOBALS['dbh']) or die('Database error');
	
	$topic = mysql_insert_id($GLOBALS['dbh']);
	mysql_query('INSERT INTO twhl_forumposts (topic, poster, post_text, post_time) VALUES ('.$topic.', "'.$user.'", "'.$body.'", '.$ttime.')',$GLOBALS['dbh']) or die('Database error');
	$post = mysql_insert_id($GLOBALS['dbh']);
  
	mysql_query('UPDATE twhl_forumtopics SET topic_lastpost = '.$post.' WHERE topic_id = '.$topic,$GLOBALS['dbh']) or die('Database error');
	mysql_query('UPDATE twhl_forums SET forum_topics = forum_topics + 1, forum_posts = forum_posts + 1, forum_lastpost = '.$post.' WHERE forum_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  
  
	if ($user > 0) {
		mysql_query('UPDATE twhl_users SET ForumPosts = ForumPosts + 1 WHERE UserID = '.$user,$GLOBALS['dbh']) or die('Database error');
	}
	
  ?>
  <SCRIPT LANGUAGE="JavaScript">
    window.setTimeout('window.location="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>"',3000);
  </SCRIPT>
  <DIV ALIGN="center">
  <FONT COLOR="green"><B>Topic posted successfully!</B></FONT>
  <P>
  Click <A HREF="<?=SELF.qstr('id',$id,qstr('action','viewforum',''))?>">here</A> to return to the forum<BR>
  Click <A HREF="<?=SELF.qstr('id',$topic,qstr('action','viewthread',''))?>">here</A> to see your topic
  </DIV>
  <?
  return;
}

function create_reply($id) {

  if (!isset($_SESSION['uid'])) {
    ?>
    <FONT COLOR="red"><b>You must be logged in to use the forums.</b></FONT><P>
    <?
    return;
  }

  // id is topic
  $body = preparebody($_POST['body']);
  $user = (isset($_SESSION['uid']))?$_SESSION['uid']:0;
  $ttime = getztime();
  
  if ($body == '') {
    ?>
  <SCRIPT LANGUAGE="JavaScript">
    window.setTimeout('window.location="<?=SELF.qstr('id',$id,qstr('action','viewthread',''))?>"',5000);
  </SCRIPT>
  <DIV ALIGN="center">
  <FONT COLOR="red"><B>Cannot submit empty posts!</B></FONT>
  <P>
  Click <A HREF="<?=SELF.qstr('id',$id,qstr('action','viewthread',''))?>">here</A> to go back<BR>
  </DIV>
    <?
    return;
  }
  
  $ptopicrs = mysql_query('SELECT forum, topic_open FROM twhl_forumtopics WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  if (mysql_num_rows($ptopicrs) == 1) {
    $ptopicrs = mysql_fetch_array($ptopicrs);
    if ($ptopicrs['topic_open'] != 1) die('This topic has been closed. <A HREF="'.SELF.'">Back</A>');
    $forum = $ptopicrs['forum'];
  }
  else {
    die('Database result error. <A HREF="'.SELF.'">Back</A>');
  }

  	$lastpostquery = mysql_query("SELECT post_text FROM twhl_forumposts WHERE topic = " . $id . " ORDER BY post_id DESC LIMIT 1", $GLOBALS['dbh']) or die ("Curses!");
	$lastpost = mysql_result ($lastpostquery, 0);
	
	if ($lastpost == $body) {
		print ("<FONT COLOR=\"red\"><b>Post was identical to previous! Duplicate not posted.</b></FONT><P>");
		return;
	}
  
  mysql_query('INSERT INTO twhl_forumposts (topic, poster, post_text, post_time) VALUES ('.$id.', "'.$user.'", "'.$body.'", '.$ttime.')',$GLOBALS['dbh']) or die('Database error');
  $post = mysql_insert_id($GLOBALS['dbh']);
  mysql_query('UPDATE twhl_forumtopics SET topic_replies = topic_replies + 1, topic_lastpost = '.$post.' WHERE topic_id = '.$id,$GLOBALS['dbh']) or die('Database error');
  mysql_query('UPDATE twhl_forums SET forum_posts = forum_posts + 1, forum_lastpost = '.$post.' WHERE forum_id = '.$forum,$GLOBALS['dbh']) or die('Database error');
  if ($user > 0) {
    mysql_query('UPDATE twhl_users SET ForumPosts = ForumPosts + 1 WHERE UserID = '.$user,$GLOBALS['dbh']) or die('Database error');
  }

  ?>
  <SCRIPT LANGUAGE="JavaScript">
    window.setTimeout('window.location="<?=SELF.qstr('id',$forum,qstr('action','viewforum',''))?>"',5000);
  </SCRIPT>
  <DIV ALIGN="center">
  <FONT COLOR="green"><B>Reply posted successfully!</B></FONT>
  <P>
  <?if (!isset($_SESSION['uid'])) {?>
  <FONT COLOR="red">Note: You were not logged-in, so your post is marked as anonymous.</FONT><P>
  <?}?>
  Click <A HREF="<?=SELF.qstr('id',$forum,qstr('action','viewforum',''))?>">here</A> to return to the forum<BR>
  Click <A HREF="<?=SELF.qstr('id',$id,qstr('action','viewthread',qstr('pg','last','')))?>">here</A> to see your post<BR>
  </DIV>
  <?
  return;
}

function preview_post() {
# NOT GOOD ENOUGH YET
  $body = preparebody($_POST['body']);
  ?>
<?=tblhead()?>
<DIV ALIGN="center"><B>Previewing Post</B></DIV>
<DIV ALIGN="right"><A HREF="#reply">Continue Editing</A> | Post</DIV>
<?=tblbody()?>
<TR><TD BGCOLOR="#F8F6DE">
</DIV>
<?=post_format($body)?>
</TD></TR>
<?=tblfoot()?>
<P>

  <?
  return;

}

?>