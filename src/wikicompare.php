<?
	require_once('functions.php');
	require_once('logins.php');
	include 'sde/diff.php';
	
	if (!isset($passon_oldid)) $oldid = mysql_real_escape_string($_GET['old']);
	else $oldid = $passon_oldid;
	
	if (!isset($passon_newid)) $newid = mysql_real_escape_string($_GET['new']);
	else $newid = $passon_newid;
	
	$oldq = mysql_query("SELECT * FROM wikientries WHERE entryID = '$oldid'");
	if (mysql_num_rows($oldq) == 0)
	{
		echo 'Error: Entry not found';
		exit;
	}
	$oldr = mysql_fetch_array($oldq);
	$text1 = $oldr['entrycontent'];
	
	$newq = mysql_query("SELECT * FROM wikientries WHERE entryID = '$newid'");
	if (mysql_num_rows($newq) == 0)
	{
		echo 'Error: Entry not found';
		exit;
	}
	$newr = mysql_fetch_array($newq);
	$text2 = $newr['entrycontent'];
	
	function wikidiff($old_t,$new_t)
	{
		$thediff = new DifferenceEngine($old_t,$new_t);
		return $thediff->showDiffPage();
	}
	
	echo wikidiff($text1,$text2);
?>