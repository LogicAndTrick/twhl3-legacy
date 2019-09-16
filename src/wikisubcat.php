<?
	$getsat = mysql_real_escape_string($_GET['sub']);
	
	$satq = mysql_query("SELECT * FROM wikititles WHERE titlesubcat = '$getsat' AND titleisactive = '1' ORDER BY titletitle ASC");
	
	//if (mysql_num_rows($satq) == 0) fail("Subcategory not found.","wiki.php");
	
	$satnaq = mysql_query("SELECT * FROM wikisubcats WHERE subcatID = '$getsat'");
	if (mysql_num_rows($satnaq) == 0) fail("Subcategory not found.","wiki.php");
	$satnar = mysql_fetch_array($satnaq);
	$subname = $satnar['subcatname'];
	$getcat = $satnar['subcatcat'];
	
	$catnaq = mysql_query("SELECT * FROM wikicats WHERE catID = '$getcat'");
	if (mysql_num_rows($catnaq) == 0) fail("Category not found.","wiki.php");
	$catnar = mysql_fetch_array($catnaq);
	$catname = $catnar['catname'];
	$catid = $catnar['catID'];
	
?>
<div class="single-center">
	<h1><?=$subname?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <?=$subname?><? if (isset($_SESSION['wiki_lvl']) && ($_SESSION['wiki_lvl'] > 0)) { ?> &gt; <a href="wiki.php?new=<?=$getsat?>">New Entry</a><? } ?></h2>
	<?=(mysql_num_rows($satq)!=0)?tri_column($satq,"wiki.php?id=","titletitle","titleID"):'<p class="single-center-content">There aren\'t any Entries in this section yet! Click the \'New Entry\' link above to add one, if you are logged in.</p>'?>
</div>