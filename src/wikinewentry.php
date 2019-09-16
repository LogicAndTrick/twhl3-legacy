<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	$getsat = mysql_real_escape_string($_GET['id']);
	
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php");
	
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
	
	$entrysubcat = $getsat;
	$entrygame = 0;
	$entrytitle = htmlfilter($_POST['entname']);
	$entrysubtitle = '';
	$entrycontent = htmlfilter($_POST['content']);
	$entryrevision = 0;
	$entryisactive = 1;
	$entrydate = gmt("U");
	$entryverified = ($lvl>=20)?'1':'0';
	
	mysql_query("INSERT INTO wikititles (titlesubcat,titlegame,titletitle,titlesubtitle,titlerevisions,titleisactive,titledate,titlecoms) VALUES ('$entrysubcat','$entrygame','$entrytitle','$entrysubtitle','$entryrevision','$entryisactive','$entrydate','0')");
	$newidr = mysql_fetch_array(mysql_query("SELECT * FROM wikititles ORDER BY titleID DESC LIMIT 1"));
	$newid = $newidr['titleID'];
	mysql_query("INSERT INTO wikientries (entrytitle,entryuser,entrycontent,entryrevision,entrydetails,entryisactive,entrydate,entryverified) VALUES ('$newid','$usr','$entrycontent','0','Initial Version','1','$entrydate','$entryverified')");
	
	header("Location: wiki.php?id=".$newid);
?>