<?
	$getpage=mysql_real_escape_string($_GET['page']);

	$newscount = 10;
	$startat = 0;
	$page = 1;
	
	//get newses
	$numnews = mysql_num_rows(mysql_query("SELECT * FROM news"));
	
	$lastpage = ceil($numnews/$newscount);
	
	// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
	if ($getpage == "last") $page = $lastpage;
	elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
	elseif (($getpage-1)*$newscount > $numnews) $page = 1;
	else $page = $getpage;
	
	$startat = ($page-1)*$newscount;
	
	$url = "news.php?page=";
	
?>
<div class="single-center">
	<h1>News Archives</h1>
	<h2><span class="page-index"><?=makeindex($page,5,$lastpage,$url)?></span>&nbsp;</h2>
	<p class="single-center-content">
		Welcome to TWHL's News Archive! Feel free to peruse a giant backlog of old news posts for your own amusement.
	</p>	
</div>
<div class="single-center" id="gap-fix-bottom">	
	<h1 class="no-bottom-border">The News</h1>
<?
	$newsq = mysql_query("SELECT newsID,userID,uid,lvl,news.title,date,newsart,stat_coms,avtype,levelname,allowtitle,usetitle,users.title AS usertitle FROM news LEFT JOIN users ON newscaster = userID LEFT JOIN userlevels on lvl = levelnum ORDER BY newsID DESC LIMIT $startat,$newscount");
	if (mysql_num_rows($newsq) > 0)
	{
		while ($newr = mysql_fetch_array($newsq))
		{
			$nid=$newr['newsID'];
			$postrid=$newr['userID'];
			$postr=$newr['uid'];
			$postrlvl=$newr['lvl'];
			$title=$newr['title'];
			$pdate=timezone($newr['date'],$_SESSION['tmz'],"F jS, Y");
			$messg=news_format($newr['newsart']);
			$avtype=$newr['avtype'];
			
			$avatar=getresizedavatar($postrid,$avtype,100);
			
			$userlvlname=$newr['levelname'];
			if (($newr['allowtitle'] > 0 || $newr['lvl'] >= 20) && $newr['usetitle'] > 0 && $newr['usertitle'] != "") $userlvlname = $newr['usertitle'];
?>
	<span class="date">Posted <?=$pdate?></span>
	<h2 class="news-archive"><?=$title?></h2>
	<span class="news-info">
		<img src="<?=$avatar?>" alt="<?=$postr?>" /><br />
		<a href="user.php?id=<?=$postrid?>"><?=$postr?></a><br />
		<?=$userlvlname?><br />
		<a href="news.php?id=<?=$nid?>">[<?=$newr['stat_coms']?> comment<?=($newr['stat_coms']==1)?'':'s'?>]</a><? if (isset($lvl) && $lvl >=35) { ?><br />
		<a href="news.php?edit=<?=$nid?>">Edit</a>/<a href="news.php?delete=<?=$nid?>">Delete</a><? } ?>
	</span>
	<p class="news-content">
		<?=$messg?>
	</p>
<?
		}
	}
	else
	{
?>
	<p class="single-center-content">
		There is no news!
	</p>
<?
	}
?>
</div>