<?

	$getpage=trim($_GET['page']);
	$getsort=trim($_GET['sort']);
	
	$sort = 'userID DESC';
	if (isset($getsort) && ($getsort=='name')) $sort = 'uid ASC';

	// users per page.
	$usercount = 51;
	// just declaring here from habit. i've been doing java at uni this semester.
	$startat = 0;
	// see above. i dont like assigning variables that aren't declared. gives me the heeby-jeebies.
	$page = 1;
	
	// get the number of users - for page index
	$checkpageq = mysql_query("SELECT count(*) AS cnt FROM users WHERE lvl > -1");
	$checkpages=mysql_fetch_array($checkpageq);
	$numusers = $checkpages['cnt'];
	
	// for example, if there are 4000 users with 51 users per page, 4000/51 = 78.43, ceil(78.43) = 79. 79 pages. which is correct.
	$lastpage = ceil($numusers/$usercount);
	
	// various techniques for getting the current page. last = last page, nothing = first page. otherwise its the page in the URL. page 1 if it is invalid.
	if ($getpage == "last") $page = $lastpage;
	elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
	elseif (($getpage-1)*$threadcount > $numthreads) $page = 1;
	else $page = $getpage;
	
	$startat = ($page-1)*$usercount;
	
	$url = "user.php?" . (($sort == 'uid ASC')?"sort=name&amp;":"") . "page=";
	
	$mod = false;
	if ($_SESSION['lvl'] > 4) $mod = true;

	$result = mysql_query("SELECT * FROM users WHERE lvl > -1 ORDER BY $sort LIMIT $startat,$usercount");
	
	$counter = 0;

?>
<div class="single-center">
	<h1>The TWHL Community</h1>
	<span class="page-index">
		<?=makeindex($page,5,$lastpage,$url)?>
	</span>	
	<h2>Sort by: <a href="user.php?sort=name"><?=($sort == 'uid ASC')?'[Username]':'Username'?></a> | <a href="user.php"><?=($sort != 'uid ASC')?'[Date]':'Date'?></a></h2>	
	<p class="single-center-content">
		These lucky people are part of the TWHL family.<? if (!isset($usr)) { ?> Get yourself in the list as well by <a href="register.php">registering</a>!<? } ?>
	</p>
	<table class="member-list">
<?

	while($row = mysql_fetch_array($result))
	{
		$user=$row['uid'];
		$userid=$row['userID'];
		$access=$row['lvl'];
		$avtype=$row['avtype'];
		$userip=$row['ipadd'];
		
		$avatar=getavatar($userid,$avtype,true);
		
		if ($counter == 0) {
			echo '<tr>';
		}
		
	?>
			<td>
				<span class="avatar">
					<img src="<?=$avatar?>" alt="avatar" />
				</span>
				<p class="info">
					<a href="user.php?id=<?=$userid?>"><?=$user?></a><br />
					<? if (isset($lvl) && $lvl >= 40) { ?>[<?=$userip?>]<? } ?><br />
					<?=($access >= 20)?'<img src="http://twhl.info/gfx/shield_m.gif" alt="mod" />':''?>
					<? //<img src="http://twhl.info/gfx/shield_c.gif" alt="compo" /> ?>
					<? //<img src="http://twhl.info/gfx/shield_v.gif" alt="maps" /> ?>
				</p>
			</td>
	<?
		$counter++;
		if ($counter >= 3) {
			$counter = 0;
			echo '</tr>';
		}
	}
	if ($counter > 0) {
		echo '</tr>';
	}
?>
	</table>		
</div>