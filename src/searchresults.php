<?
	$problem = "The search location is not valid, or there are no search parameters.";
	$back = "index.php";
	$user_lvl = 0;
	if (isset($lvl) && $lvl != "") $user_lvl = $lvl;
	if (isset($_GET['searchlocation']) && isset($_GET['searchstring']))
	{
		$sstr = mysql_real_escape_string(stripslashes($_GET['searchstring']));
		$valid = true;
		$row1 = "";
		$row2 = "";
		$row1link = "";
		$usersearch = false;
		switch ($_GET['searchlocation'])
		{
			case "tuts":
				$searchq = "SELECT *, tutorials.name AS search1, tutorials.tutorialID AS search1id, tutorialcats.name AS search2 FROM tutorialpages LEFT JOIN tutorials ON tutorialpages.tutorialid = tutorials.tutorialID LEFT JOIN tutorialcats ON catID = sectionID WHERE (" . search_all($sstr,Array("content","description","tutorials.name","topics")) . ") AND tutorialcats.level <= $user_lvl AND tutorials.waiting = 0 GROUP BY tutorialpages.tutorialid ORDER BY tutorialpages.tutorialid DESC";
				$row1 = "Tutorial";
				$row2 = "Category";
				$row1link = "tutorial.php?id=";
				break;
			case "ents":
				$searchq = "SELECT *, entname AS search1, entID AS search1id, gamename AS search2 FROM entities LEFT JOIN entitygames ON entgame = entgameID LEFT JOIN mapgames ON entmapgame = gameID WHERE " . search_all($sstr,Array("entname","enttext")) . " ORDER BY entID DESC";
				$row1 = "Entity";
				$row2 = "Category";
				$row1link = "entity.php?id=";
				break;
			case "gloss":
				$searchq = "SELECT *, glossname AS search1, glossID AS search1id, glosscatname AS search2 FROM glossary LEFT JOIN glossarycats ON glosscat = glosscatID WHERE " . search_all($sstr,Array("glossname","glossaltname","glosstext")) . " ORDER BY glossID DESC";
				$row1 = "Glossary Entry";
				$row2 = "Category";
				$row1link = "glossary.php?id=";
				break;
			case "maps":
				$searchq = "SELECT *, name AS search1, mapID AS search1id, gamename AS search2 FROM maps LEFT JOIN mapgames ON game = gameID WHERE " . search_all($sstr,Array("name","info")) . " ORDER BY mapID DESC";
				$row1 = "Map";
				$row2 = "Game";
				$row1link = "vault.php?map=";
				break;
			case "users":
				$searchq = "SELECT *, uid AS search1, userID AS search1id, CONCAT(SUBSTRING(bio,1,100),'...') AS search2 FROM users WHERE " . search_all($sstr,Array("bio","uid")) . " ORDER BY userID DESC";
				$row1 = "User";
				$row2 = "Bio";
				$row1link = "user.php?id=";
				$usersearch = true;
				break;
			case "forums":
				/*$searchq = "SELECT *, threads.name AS search1, threads.threadID AS search1id, forumcats.name AS search2 FROM posts LEFT JOIN threads ON posts.threadid = threads.threadID LEFT JOIN users ON threads.ownerid = users.userID LEFT JOIN posts AS posts2 ON threads.stat_lastpostid = posts2.postID LEFT JOIN forumcats ON threads.forumcat = forumID WHERE " . search_all($sstr,Array("threads.name","posts.posttext","users.uid")) . " AND forumcats.accesslevel <= $user_lvl GROUP BY threads.threadid ORDER BY posts2.postdate DESC";*/

				$searchq = 
"SELECT pq.threadid AS search1id, threads.name AS search1, forumcats.name AS search2
FROM (

SELECT DISTINCT threadid
FROM posts
WHERE MATCH (
posts.posttext
)
AGAINST (
'" . $sstr . "'
IN BOOLEAN
MODE
)
) AS pq
LEFT JOIN threads ON pq.threadid = threads.threadID
LEFT JOIN forumcats ON threads.forumcat = forumID
WHERE forumcats.accesslevel <= $user_lvl
GROUP BY pq.threadid
ORDER BY threads.stat_lastpostid DESC ";

				$row1 = "Topic";
				$row2 = "Forum";
				$row1link = "forums.php?thread=";
				break;
			case "forumsnew":
				break;
				//$searchq = "SELECT *, thname AS search1, threadID AS search1id, fname AS search2 FROM forum_index WHERE " . search_all($sstr,Array("thname","posttext")) . " AND accesslevel <= $user_lvl GROUP BY threadID ORDER BY lastpost DESC";
				$row1 = "Topic";
				$row2 = "Forum";
				$row1link = "forums.php?thread=";
				break;
			default:
				$valid = false;
				break;
		}
		include 'searchview.php';
	}
	else include 'failure.php';

?>