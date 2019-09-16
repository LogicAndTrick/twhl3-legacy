<?php	
		if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php");	

		$theid=mysql_real_escape_string($_GET['viewprop']);
		$result = mysql_query("SELECT propuser,propdifficulty,propgame,propdate,propname,propkeywords,propdetails,accepted,rejectreason,uid,avtype FROM tutorialproposals LEFT JOIN users ON propuser = userID WHERE propID = '$theid'");
		$row = mysql_fetch_array($result);
		
		$checki=mysql_query("SELECT propcomID,proposalID,comuser,comdate,accept,comtext,uid FROM tutorialpropcoms LEFT JOIN users ON comuser = userID WHERE proposalID = '$theid' AND comuser = " . $_SESSION['usr'] . " AND accept != 0");
		$decided = false;
		if (mysql_num_rows($checki) != 0) $decided = true;
		
		$result2=mysql_query("SELECT propcomID,proposalID,comuser,comdate,accept,comtext,uid,avtype FROM tutorialpropcoms LEFT JOIN users ON comuser = userID WHERE proposalID = '$theid' ORDER BY comdate ASC");
		
		$acc=$row['accepted'];
		if ($acc==0) $status="Open";
		elseif ($acc==1) $status="Rejected - ".$row['rejectreason'];
		elseif ($acc==2) $status="Accepted";
		
		$theuser=$row['propuser'];		
		
		$acntz=mysql_fetch_array(mysql_query("SELECT count(*) as count FROM tutorialproposals WHERE propuser='$theuser' AND accepted = 2"));
		$acnt=$acntz['count'];
		
		$rcntz=mysql_fetch_array(mysql_query("SELECT count(*) as count FROM tutorialproposals WHERE propuser='$theuser' AND accepted = 1"));
		$rcnt=$rcntz['count'];
		
		$topix="Other";
		if ($row['propgame'] == 0) $topix = "GoldSource";
		elseif ($row['propgame'] == 1) $topix = "Source";
		
		$diff="Advanced";
		if ($row['propdifficulty'] == 0)
			$diff = "Beginner";
		elseif ($row['propdifficulty'] == 1)
			$diff = "Intermediate";
			
			
		$ycntz=mysql_fetch_array(mysql_query("SELECT count(*) as count FROM tutorialpropcoms WHERE proposalID='$theid' AND accept = 1"));
		$ycnt=$ycntz['count'];
		
		$ncntz=mysql_fetch_array(mysql_query("SELECT count(*) as count FROM tutorialpropcoms WHERE proposalID='$theid' AND accept = 2"));
		$ncnt=$ncntz['count'];
		
?>

<div class="single-center">
	<h1><?=$row['propname']?></h1>
	<h2><a href="tutorial.php">Tutorials</a> &gt; <a href="tutorial.php?viewprops=1">Proposals</a> &gt; <?=$row['propname']?></h2>
	<span class="right-avatar">
		<img src="<?=getresizedavatar($row['propuser'],$row['avtype'],100)?>" alt="avatar" />	
	</span>			
	<p class="right-info">
		By <a href="user.php?id=<?=$row['propuser']?>"><?=$row['uid']?></a> <a href="tutorial.php?user=<?=$row['propuser']?>">(More from this user)</a><br/>
		<?=$acnt?> Accepted, <?=$rcnt?> Rejected (<a href="tutorial.php?userprop=<?=$row['propuser']?>">View History</a>)<br/>
		<strong><?=date("F jS, Y",$row['propdate'])?></strong><br/>
		<?=$diff?><br/>	
		Status: <?=$status?><br/>
		<img src="images/tutyes.png" alt="yes" /> <?=$ycnt?> <img src="images/tutno.png" alt="no" /> <?=$ncnt?><br/>
	</p>
</div>
<div class="single-center" id="gap-fix">
	<h1>Proposal</h1>					
	<p class="single-center-content">
		<?=$row['propdetails']?>
	</p>
<?
	if ($acc == 0)
	{
?>
	<div style="margin: 12px;">
		<form action="tutpropa.php?id=<?=$theid?>" method="post" style="display:inline;">
			<fieldset style="display:inline;">
				Final Decision:
				<input value="Accept" type="submit" />
			</fieldset>
		</form>
		<form action="tutpropr.php?id=<?=$theid?>" method="post" style="display:inline;">
			<fieldset style="display:inline;">
				&nbsp;&nbsp;Or&nbsp;&nbsp;
				<input name="reason" size="26" value="Reason For Rejection" type="text" />
				<input value="Reject" type="submit" />
			</fieldset>
		</form>
	</div>
<?
	}
?>
</div>	
<div class="single-center">
	<h1 class="no-bottom-border">Comments</h1>
	<div class="comments">
<?
		$alt=true;
		while($row2 = mysql_fetch_array($result2))
		{ 
			$alt = !$alt;
?>
		<div class="comment-container<? if ($alt) echo '-alt'; ?>">
			<span class="avatar"><img src="<? echo getavatar($row2['comuser'],$row2['avtype'],true); ?>" alt="avatar" /></span>	
			<span class="name"><strong><a href="#"><?=$row2['uid']?></a> says:<? if ($row2['accept'] != 0) echo ($row2['accept']==1) ? ' <span class="proposal-yes">YES</span>' : ' <span class="proposal-no">NO</span>';?></strong></span>
			<span class="date"><?=date("F jS, Y, H:i A",$row2['comdate'])?></span>
			<div class="text"><?=$row2['comtext']?></div>
		</div>
<?
		}
?>
		<div class="comment-box">
<?
		if ($acc==0)
		{
			if (!$decided)
			{
?>
			<form action="tutpropcomment.php?comment=<?=$theid?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					Accept Proposal?
					<select name="accept">
						<option value="0">Undecided&nbsp;&nbsp;</option>
						<option value="1">Yes</option>
						<option value="2">No</option>
					</select><br />
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
<?
			}
			else
			{
?>
			<form action="tutpropcomment.php?comment=<?=$theid?>" method="post">
				<fieldset>
					<input name="accept" type="hidden" value="0" />
					<textarea name="comment" rows="10" cols="76"></textarea>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
<? 
			}
		} 
		else
		{
?>
			<p class="single-center-content">Closed for Comments</p>
<?
		}
?>
		</div>	
	</div>
</div>