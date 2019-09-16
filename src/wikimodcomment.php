<?

	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php");
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php");
	$getcomm = mysql_real_escape_string($_GET['comment']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","wiki.php");
	
	if (isset($lvl) && ($lvl >= 20)) $ceditq = mysql_query("SELECT * FROM wikicomments LEFT JOIN users ON commentuser = userID WHERE commentID = '$getcomm'");
	else $ceditq = mysql_query("SELECT * FROM wikicomments LEFT JOIN users ON commentuser = userID WHERE commentID = '$getcomm' AND commentuser = '$usr'");
	
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","wiki.php");
	
	$mod = false;
	if ($_SESSION['lvl'] >= 20) $mod = true;
	
	$cedr = mysql_fetch_array($ceditq);
	$ctitle = $cedr['commenttitle'];
	$ctext = $cedr['commentcontent'];
	$cuser = $cedr['commentuser'];
	$cusername = $cedr['uid'];
	
	$getenty = $ctitle;
	
	$entyq = mysql_query("SELECT * FROM wikititles WHERE titleID = '$getenty'");
	
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
	
	if (isset($_GET['delete']))
		include 'wikideletecomment.php';
	else
		include 'wikieditcomment.php';
?>