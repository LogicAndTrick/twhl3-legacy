<?

function delete_post($postid)
{
	// PURPOSE: deletes one post.
	// Must be connected to mysql! 
	// Get post (to retrieve user id)
	$del_res = mysql_query("SELECT * FROM posts WHERE postID='$postid'");
	// Return if post doesn't exist or something really weird is going on (a.k.a The Apocalypse)
	if (mysql_num_rows($del_res) != 1) return;
	// Else get info
	$del_row = mysql_fetch_array($del_res);
	$poor_user = $del_row['posterid'];
	// Reduce user's post count by 1
	mysql_query("UPDATE users SET stat_posts = stat_posts-1 WHERE userID = '$poor_user'");
	// Then delete the post. updating last thread/post etc in the forum is handled by the caller, not in here.
	$result = mysql_query("DELETE FROM posts WHERE postID='$postid'");
}

function delete_thread_posts($threadid)
{
	// PURPOSE: deletes all the posts in a thread (NOT the thread itself)
	// Get all posts in the thread
	$thd_del_res = mysql_query("SELECT * FROM posts WHERE threadid='$threadid'");
	// Check that the thread exists
	if (mysql_num_rows($thd_del_res) <= 0) return;
	// Cycle through all posts, deleting each one
	while ($thd_del_row = mysql_fetch_array($thd_del_res))
	{
		delete_post($thd_del_row['postID']);
	}
}

?>