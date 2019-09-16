<div class="center-content center-threads">
	<h1 class="no-bottom-border"><a href="forums.php">Active Forum Threads</a></h1>
<?
    $user_lvl = 0;
    if (isset($lvl) && ($lvl != "")) $user_lvl = mysql_real_escape_string($lvl);

    $result = mysql_query(
        "SELECT
            T.ForumCat,
            F.Name AS ForumName,
            T.ThreadID,
            T.Name as ThreadName,
            U.UserID,
            U.UID as Username,
            P.PostText,
            P.PostDate AS PostDate
        FROM threads T
        LEFT JOIN forumcats F
            ON F.forumid = T.forumcat
        LEFT JOIN posts P
            ON T.stat_lastpostid = P.postid
        LEFT JOIN users U
            ON P.posterid = U.userid
        WHERE F.accesslevel <= '$user_lvl'
        ORDER BY T.stat_lastpostid DESC
        LIMIT 5");

    while($row = mysql_fetch_array($result))
    {
        $forumid = $row['ForumCat'];
        $forumname = $row['ForumName'];
        $threadid = $row['ThreadID'];
        $threadname = $row['ThreadName'];
        $postuserid = $row['UserID'];
        $postusername = $row['Username'];
        
        $posttext = $row['PostText'];
        $postdate = timezone($row['PostDate'], $_SESSION['tmz'], "d M y, H:i");
        if (strlen($posttext) > 150) $posttext = substr($posttext, 0, 150) . "...";
        $posttext = post_format($posttext, true);
        $inner = mysql_query(
            "SELECT
                UserID,
                Username
            FROM (
                SELECT
                    U.UserID,
                    U.UID AS Username,
                    MAX(P.PostDate) AS Max
                FROM posts P
                LEFT JOIN users U
                    ON P.posterid = U.userid
                WHERE threadid = '$threadid'
                GROUP BY U.uid
            ) IQ
            ORDER BY IQ.Max DESC
            LIMIT 1,5");
        $ni = mysql_num_rows($inner);

?>
	<span class="forum-info">[By <a href="user.php?id=<?=$postuserid?>"><?=$postusername?></a>, <?=$postdate?>]</span>
	<h2 class="top-border"><a href="forums.php?thread=<?=$threadid?>&amp;page=last"><?=$threadname?></a></h2>
	<p class="latest-threads-left">
		<?=$posttext?>
	</p>
    <div class="latest-threads-right">
       <span>Other users in this thread...</span>
        <ul>
<?
        if ($ni > 0)
        {
            while ($ur = mysql_fetch_array($inner))
            {
                $innerpostuserid = $ur['UserID'];
                $innerpostusername = $ur['Username'];
?>
            <li><a href="user.php?id=<?=$innerpostuserid?>"><?=$innerpostusername?></a></li>
<?
            }
        }
        else
        {
?>
            <li>None</li>
<?
        }
?>
        </ul>
    </div>
    <div class="clearfix"></div>
<?
    }
?>
</div>