<?
	include 'middle.php';
	
	if (!(isset($lvl) && ($usr >= 20))) fail("You are not allowed view this page.","wiki.php",true);
	$next_ent = mysql_query("SELECT * FROM wikientries WHERE entryverified = '0' ORDER BY entrydate DESC LIMIT 1");
	$next_com = mysql_query("SELECT * FROM wikicomments WHERE commentverified = '0' ORDER BY commentdate DESC LIMIT 1");
	$ent_time = -1;
	$ent_id = -1;
	$com_time = -1;
	$com_id = -1;
	
	if (mysql_num_rows($next_ent) > 0)
	{
		$enr = mysql_fetch_array($next_ent);
		$ent_id = $enr['entryID'];
		$ent_time = $enr['entrydate'];
	}
	if (mysql_num_rows($next_com) > 0)
	{
		$cmr = mysql_fetch_array($next_com);
		$com_id = $cmr['entryID'];
		$com_time = $cmr['entrydate'];
	}
	
	if ($ent_time == -1 && $com_time == -1) header("Location: wiki.php?valdone");
	elseif ($ent_time >= $com_time) header("Location: wiki.php?vale=".$ent_id);
	elseif ($ent_time < $com_time) header("Location: wiki.php?valc=".$com_id);
	else fail("Something bad happened.","wiki.php",true);
	
?>