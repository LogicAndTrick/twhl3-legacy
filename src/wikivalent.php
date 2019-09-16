<?
	$getenty = mysql_real_escape_string($_GET['vale']);
	
	if (!(isset($lvl) && ($usr >= 20))) fail("You are not allowed view this page.","wiki.php");
	
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
	<h2><a href="wiki.php">Wiki</a> &gt; Validator &gt; <a href="wiki.php?id=<?=$title_id?>"><?=$entr['titletitle']?></a></h2>
	<p class="single-center-content">
		Take a look at the comparison and make sure it's all good. You can say it's fine, and the entry will be verified. You can also choose to revert to the old revision. Doing this will do the exact same as if you reverted to this version though history.
	</p>
	<p class="single-center-content">
		Doing either of these will take you to another unverified page to be checked. If you want to revert to a page other than the very previous version, <a href="wiki.php?history=<?=$title_id?>">click here to go to the entry's history page</a>. Note that doing so will exit the Validator.
	</p>
	<form action="wikivaldecide.php?id=<?=$getenty?>" method="post">
		<table style="width: 667px;">
			<tr>
				<td style="text-align:center">&nbsp;&nbsp;&nbsp;<input type="button" value="Revert" /></td>
				<td style="text-align:center"><input type="button" value="All Good" /></td>
			</tr>
		</table>
	</form>
<?
	include 'wikicompare.php';
?>
</div>