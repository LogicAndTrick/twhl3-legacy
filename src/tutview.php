<?php	

$getid=mysql_real_escape_string($_GET['id']);
$getpage=mysql_real_escape_string(trim($_GET['page']));

// default page
$page = 1;

if (!isset($getpage) || $getpage < 1 || $getpage > 5 || $getpage == "" || !is_numeric($getpage)) $page = 1;
else $page = $getpage;

$exists = false;
$result = mysql_query("SELECT pageID,tutorials.name AS tutname,difficulty,date,example,examplesize,examplecont,examplenotes,topics,rating,ratings,sectionID,tutorialcats.name AS catname,userID,uid,avtype,waiting FROM tutorialpages LEFT JOIN tutorials ON tutorials.tutorialID = tutorialpages.tutorialID LEFT JOIN tutorialcats ON catID = sectionID LEFT JOIN users ON authorid = userID WHERE tutorialpages.tutorialid = '$getid' AND tutorialpages.page = '$page' AND (tutorialpages.isactive = '1' OR waiting = '1')");
$numpages = mysql_num_rows($result);
if ($numpages > 0)
{
	$exists = true;
}
if (($exists) && isset($getid) && ($getid!="") && is_numeric($getid))
{

	$row = mysql_fetch_array($result);
	$tutname=$row['tutname'];
	$catname=$row['catname'];
	$difficulty=$row['difficulty'];
	$date=$row['date'];
	$example=$row['example'];
	$examplesize=$row['examplesize'];
	$examplecont=$row['examplecont'];
	$examplenotes=$row['examplenotes'];
	$topics=$row['topics'];
	$rating=$row['rating'];
	$ratings=$row['ratings'];
	$sectionid=$row['sectionID'];
	$authorid=$row['userID'];
	$author=$row['uid'];
	$avtype=$row['avtype'];
	
	$waiting=$row['waiting'];
	
	$exdl = false;
	if ($example != '') $exdl = true;
	
	$diff="Advanced";
	if ($difficulty == 0)
		$diff = "Beginner";
	elseif ($difficulty == 1)
		$diff = "Intermediate";
	
	$tutrating = 0;
	if ($ratings > 0)
		$tutrating = ceil((($rating/$ratings)*2))/2;
	
	$numfullstars = substr($tutrating,0,1);
	$halfstar = (strlen($tutrating) > 2);
	
	$exsize = 'Unknown Size';
	if ($examplesize > 1048576)
		$exsize = (round(($examplesize / 1048576)*100)/100).'MB';
	elseif ($examplesize > 500)
		$exsize = (round(($examplesize / 1024)*100)/100).'KB';
	elseif ($examplesize > 0)
		$exsize = $examplesize.' bytes';
	
	$exext = strtoupper(substr($example, strrpos($example, '.') + 1));
	
	$excont = 'No Information';
	if ($examplecont != "") $excont = $examplecont;
	
	$rated = false;
	$result = mysql_query("SELECT * FROM  tutorialcomments WHERE commuser = '".$_SESSION['usr']."' AND commtut = '$getid' AND rating > 0");
	$rater = mysql_num_rows($result);
	if ($rater > 0) $rated = true;
	
	if ($waiting == 0 || (isset($lvl) && (($authorid == $usr) || ($lvl >= 25))))
	{
?>
			<div class="single-center">
				<h1><?=$tutname?></h1>
<?
		if ($waiting == 1)
			$pageq1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$getid' ORDER BY page ASC");
		else
			$pageq1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$getid' AND isactive = 1 ORDER BY page ASC");
?>
				<span class="page-index">
				Page:
<?
		$numpage = 0;
		while($pager1 = mysql_fetch_array($pageq1))
		{
				$numpage++;
?>
				<a href="tutorial.php?id=<?=$getid?>&amp;page=<?=$pager1['page']?>"><?=(($pager1['page']==$page)?'[':'').$pager1['page'].(($pager1['page']==$page)?']':'')?></a>
<?
		}
?>
				</span>
<?

?>
				<h2><a href="tutorial.php">Tutorials</a> > <a href="tutorial.php?cat=<?=$sectionid?>"><?=$catname?></a> > <a href="tutorial.php?cat=<?=$sectionid?>&amp;diff=<?=$difficulty?>"><?=$diff?></a> > <?=$tutname?><?=(isset($lvl) && (($authorid == $usr) || ($lvl >= 25)))?' > <a href="tutorial.php?edit='.$getid.'&amp;page='.$page.'">Edit</a>':''?></h2>
				<span class="right-avatar">
					<img src="<?=getresizedavatar($authorid,$avtype,100)?>" alt="avatar" />	
				</span>
				<p class="right-info">
					By <a href="user.php?id=<?=$authorid?>"><?=$author?></a> <a href="tutorial.php?user=<?=$authorid?>">(More from this user)</a> <br />
					<strong><?=timezone($date,$_SESSION['tmz'],"jS F, Y")?></strong> <br />
					<?=$diff?> <br />	
					<?=$topics?> <br />
					
					<? for ($i = 0; $i < $numfullstars; $i++) { ?>
					<img src="images/star_full.png" alt="star" />
					<? } if ($halfstar) { ?><img src="images/star_half.png" alt="star" /><? } ?>
					<?=($row['ratings']==0)?"No Votes Yet":"(".$row['ratings']." vote".(($row['ratings']==1)?'':'s').")"?> <br />
				</p>
				<? if ($exdl) { ?>
				<div class="notes">
					<p class="download-image">
						<a href="http://twhl.info/tutorialdl/<?=$example?>"><img src="images/download.png" alt="download" /></a>
					</p>
					<span class="notes-image">
						<img src="images/examplemap.png" alt="example map" />	
					</span>
					<p class="notes-content">
						<strong>File Info</strong>: <?=$exext?>, <?=$exsize?> <br />
						<strong>Contents</strong>: <?=$excont?> <br />
						<? if ($examplenotes != "") { ?><strong>Notes</strong>: <?=$examplenotes?><? } ?>
					</p>	
				</div>
				<? } ?>
			</div>
<?
	if ($waiting == 1)
		$result = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$getid' AND page = '$page'");
	else
		$result = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$getid' AND page = '$page' AND isactive = '1'");
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_array($result);
		$result = mysql_query("UPDATE tutorials SET hits = hits + 1 WHERE tutorialid = '$getid'");
		
?>
			<div class="single-center" id="gap-fix">
				<h1><?=$tutname?><?=($numpage>1)?" - Page ".$row['page']:""?></h1>
				<?=tutorial_format($row['content'])?>
<?
		if ($waiting == 1 && $row['isactive'] < 0 && isset($lvl) && $lvl >= 25)
		{
?>
				<p class="single-center-content-center" style="border-top: 1px dotted #daa134">
					This tutorial has been marked as ready to go live by its author.<br />
					Should it go live?
				</p>
				<form action="tutdraftyes.php?id=<?=$getid?>" method="post">
					<p class="single-center-content-center">
						<input name="draftyes" value="Approve" type="submit" />
					</p>
				</form>
				<form action="tutdraftno.php?id=<?=$getid?>" method="post">
					<p class="single-center-content-center">
					<input name="noreason" value="Enter Reason for Denial Here" type="text" />
					<input name="draftno" value="Deny" type="submit" />
					</p>
				</form>
<?
		}
?>
<?
		if ($numpage > 1)
		{
?>
				<div class="tutorial-index">
<?
			if ($page > 2)
			{
?>
					<span><a href="tutorial.php?id=<?=$getid?>&amp;page=1">&lt;&lt; First</a></span>
<?
			}
			if ($page > 1)
			{
?>
					<span><a href="tutorial.php?id=<?=$getid?>&amp;page=<?=($page-1)?>">&lt; Back</a></span>
<?
			}
			for ($i=0;$i<$numpage;$i++)
			{
?>
					<span<?=($page==($i+1))?' id="current-tut-page"':''?>><a href="tutorial.php?id=<?=$getid?>&amp;page=<?=($i+1)?>"><?=($i+1)?></a></span>
<?
			}
			if ($numpage > $page)
			{
?>
					<span><a href="tutorial.php?id=<?=$getid?>&amp;page=<?=($page+1)?>">Next &gt;</a></span>
<?
			}
			if ($numpage > ($page + 1))
			{
?>
					<span><a href="tutorial.php?id=<?=$getid?>&amp;page=<?=$numpage?>">Last &gt;&gt</a></span>
<?
			}
?>
				</div>
<?
		}
?>
			</div>
			
<?
	}
	$result = mysql_query("SELECT tutorialcomments.*,uid,avtype FROM tutorialcomments LEFT JOIN users ON commuser = userID WHERE commtut = '$getid'");
?>
				
			<div class="single-center">
				<h1<?=(mysql_num_rows($result) > 0)?' class="no-bottom-border"':''?>>Comments</h1>
					<div class="comments">
						<?
						if (mysql_num_rows($result) > 0) {
							$alt = "-alt";
							while ($row = mysql_fetch_array($result)) {
								if ($alt == "") $alt = "-alt";
								else $alt = "";
						?>
						<div class="comment-container<?=$alt?>">
							<span class="avatar"><img src="<?=getavatar($row['commuser'],$row['avtype'],true)?>" alt="avatar" /></span>	
							<span class="name"><strong><a href="user.php?id=<?=$row['commuser']?>"><?=$row['uid']?></a> says:</strong><? for ($i = 0; $i < $row['rating']; $i++) { ?> <img src="images/star_full.png" alt="star" /><? } ?></span>
							<span class="date"><?=timezone($row['commtime'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
							<div class="text"><?=comment_format($row['commtext'])?></div>
						</div>
						<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
						<div class="comment-box">
						<? if (isset($_SESSION['lvl'])) { ?>
							<form action="tutaddcomment.php?id=<?=$getid?>" method="post">
								<fieldset>
									<textarea name="comment" rows="10" cols="76"></textarea>
									<? if (!$rated && ($authorid!=$_SESSION['usr'])) { ?>Rating
									<select name="rating">
										<option value="0">Do Not Rate</option>
										<option value="1">1 Star</option>
										<option value="2">2 Stars</option>
										<option value="3">3 Stars</option>
										<option value="4">4 Stars</option>
										<option value="5">5 Stars</option>
									</select><br /><? } ?>
									<input type="submit" value="Submit" />
								</fieldset>
							</form>
						<? } else { ?><div class="sorry">You must be logged in to comment.</div><? } ?>
						</div>		
					</div>
				</div>	

<?
	}
	else fail("You do not have permission to access this page","tutorial.php");
}
else fail("Tutorial not found","tutorial.php");
?>