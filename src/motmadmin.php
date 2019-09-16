<?
	if (!(isset($lvl) && ($usr >= 30))) fail("You are not allowed view this page.","index.php");
	
	// current = current tab
	// tabs = all tabs including current, in an array:
	// tab id => tab name
	function tabswitchlinkmaker($current,$alltabs)
	{
		$str = '';
		foreach($alltabs as $key => $value)
		{
			$allkeys = "'$key'";
			foreach($alltabs as $key2 => $value2) if ($key2 != $key) $allkeys .= ",'$key2'";
			if ($key != $current) $str .= ' | <a href="javascript:tabswitcher(new Array('.$allkeys.'))">'.$value.'</a>';
			else $str .= ' | '.$value;
		}
		$str=substr($str,3);
		return $str;
	}
	
	$tabs = array('vote-tab' => 'View Votes','live-tab' => 'Post MOTM','manage-tab' => 'Manage Reviews','edit-tab' => 'Edit Reviews');
	
	$tabcont = 'vote';
	if (isset($_GET['live'])) $tabcont = 'live';
	elseif (isset($_GET['manage'])) $tabcont = 'manage';
	elseif (isset($_GET['edit'])) $tabcont = 'edit';
?>

<div class="single-center" style="margin-bottom: 0;">
	<h1>User Management</h1>
	<h2><a href="motm.php">Back to MOTM Page</a></h2> 	
	<span class="left-control-image">
		<img src="images/shield_large.png" alt="large shield" />
	</span>
	<p class="single-center-content">
		Welcome to the MOTM Admin Panel. Here you can see the votes for the MOTM and choose the winner. You can also choose the reviewers for each MOTM and edit any review (as opposed to only yours in the MOTM Edit Panel). You also use this to post the MOTM to the front page and the MOTM list page when it is ready to go live.
	</p>	
</div>
<div class="single-center" style="display: <?=($tabcont=='vote')?'block':'none'?>;" id="vote-tab">
	<h1>View Votes</h1>
	<h2><?=tabswitchlinkmaker('vote-tab',$tabs)?></h2>
	<p class="single-center-content">
		Here you can see the votes for MOTM and decide who is the winner. Note that the choice is final so make sure you choose correctly! Usually the top map will be the winner but this must be done manually in the case of a tie or spamvoting. You must have Javascript enabled to use this.
	</p>
	<p class="single-center-content">
		Choose a Month:
	</p>
	<p class="single-center-content">
<?
	$lastmonth = date("n",strtotime("last month",gmt("U")));
	$lastmonthyear = date("Y",strtotime("last month",gmt("U")));

	$mrq = mysql_query("SELECT votemonth, voteyear FROM motmvotes GROUP BY voteyear, votemonth ORDER BY voteyear DESC, votemonth DESC");
	while ($mr = mysql_fetch_array($mrq)) if ($mr['voteyear'] != $lastmonthyear || $mr['votemonth'] != $lastmonth) echo '<a href="javascript:ajax_motm_votes('.$mr['voteyear'].','.$mr['votemonth'].')">'.date("F Y", mktime(0, 0, 0, $mr['votemonth'], 15, $mr['voteyear'])) . "</a><br />";
?>
	</p>
	<div id="votecont">
	</div>
</div>