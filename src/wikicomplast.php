<?
	$getenty = mysql_real_escape_string($_GET['ctl']);
	
	//$titleq = mysql_query("SELECT * FROM wikititles WHERE titleID = '$getenty'");
	//if (mysql_num_rows($titleq) == 0) fail("Entry not found.","wiki.php");
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID LEFT JOIN users ON entryuser = userID WHERE entryID = '$getenty' ORDER BY entryrevision DESC");
	if (mysql_num_rows($entyq) == 0) fail("Entry not found.","wiki.php");
	
	$entr = mysql_fetch_array($entyq);
	$getsat = $entr['titlesubcat'];
	
	$satnaq = mysql_query("SELECT * FROM wikisubcats WHERE subcatID = '$getsat'");
	if (mysql_num_rows($satnaq) == 0) fail("Subcategory not found.","wiki.php");
	$satnar = mysql_fetch_array($satnaq);
	$subname = $satnar['subcatname'];
	$getcat = $satnar['subcatcat'];
	$satid = $satnar['subcatID'];
	
	$catnaq = mysql_query("SELECT * FROM wikicats WHERE catID = '$getcat'");
	if (mysql_num_rows($catnaq) == 0) fail("Category not found.","wiki.php");
	$catnar = mysql_fetch_array($catnaq);
	$catname = $catnar['catname'];
	$catid = $catnar['catID'];
	
	$title_id = $entr['titleID'];
	
	$currentrev = $entr['entryrevision'];
	$lastrev = $currentrev-1;
	
	$lastentyq = mysql_query("SELECT * FROM wikientries WHERE entrytitle = '$title_id' AND entryrevision = '$lastrev' ORDER BY entryrevision DESC");
	if (mysql_num_rows($lastentyq) == 0) fail("Previous Revision not found.","wiki.php");
	$lastentr = mysql_fetch_array($lastentyq);
	$lastid = $lastentr['entryID'];
	
	$passon_oldid = $lastid;
	$passon_newid = $getenty;
?>
<div class="single-center">
	<h1><?=$entr['titletitle']?> - Comparison of Revisions</h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$title_id?>"><?=$entr['titletitle']?></a> &gt; Compare to Last</h2>
<?
	include 'wikicompare.php';
?>
</div>