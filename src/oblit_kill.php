<?
	include 'middle.php';
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
	$in = '';
	foreach($_POST as $pst => $val) {
		if ($val == 'on') {
			$in .= ','.mysql_real_escape_string($pst);
		}
	}
	if ($in == '') header("Location: obliterate.php");
	$in = '('.substr($in, 1).')';
	
/*
    * Deletes user account
    * Bans the user's IP
    * Deletes forum topics
    * Deletes forum posts
    * Deletes journals
    * Deletes maps
    * Deletes journal comments
    * Deletes map comments
    * Deletes MOTM comments
    * Deletes news comments
    * Deletes tutorial comments
    * Deletes wiki comments
    * Deletes PMs
    * Deletes Shouts
*/
	
	$sqls = array(
		"INSERT INTO bans (userID, IP, time, reason) SELECT userID, ipadd, -1, 'Spammer' FROM users WHERE userID IN $in",
		"UPDATE users SET uid = 'banned', pwd = 'banned', log = -1, lvl = -1 WHERE userID IN $in",
        "UPDATE threads t
         LEFT JOIN posts p ON t.stat_lastpostid = p.postid
         SET t.stat_replies = (SELECT COUNT(*)-1 FROM posts p2 WHERE p2.threadid = t.threadid AND p2.posterid NOT IN $in),
         stat_lastpostid = (SELECT MAX(p2.postid) FROM posts p2 WHERE p2.threadid = t.threadid AND p2.posterid NOT IN $in)
         WHERE p.posterid IN $in",
		"DELETE FROM posts WHERE posterid IN $in",
		"DELETE FROM threads WHERE ownerid IN $in",
		"DELETE FROM journals WHERE ownerID IN $in",
		"DELETE FROM maps WHERE owner IN $in",
		"DELETE FROM journalcomments WHERE commuser IN $in",
		"DELETE FROM mapcomments WHERE poster IN $in",
		"DELETE FROM motmcomments WHERE commuser IN $in",
		"DELETE FROM newscomments WHERE commuser IN $in",
		"DELETE FROM tutorialcomments WHERE commuser IN $in",
		"DELETE FROM wikicomments WHERE commentuser IN $in",
		"DELETE FROM pminbox WHERE pmfrom IN $in",
		"DELETE FROM pminbox WHERE pmto IN $in",
		"DELETE FROM shouts WHERE uid IN $in"
	);
	
	
	foreach ($sqls as $sql) {
		mysql_query($sql);
	}
	
	header("Location: obliterate.php?success");
?>