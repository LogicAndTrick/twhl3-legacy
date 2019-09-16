<?
	$getcat = mysql_real_escape_string($_GET['cat']);
	
	$catq = mysql_query("SELECT * FROM wikisubcats WHERE subcatcat = '$getcat' AND subcatopen = '1'");
	if (mysql_num_rows($catq) == 0) fail("Category not found.","wiki.php");
	
	$catnaq = mysql_query("SELECT * FROM wikicats WHERE catID = '$getcat'");
	if (mysql_num_rows($catnaq) == 0) fail("Category not found.","wiki.php");
	$catnar = mysql_fetch_array($catnaq);
	$catname = $catnar['catname'];
	$catdesc = $catnar['catdesc'];
	
?>
<div class="single-center">
	<h1><?=$catname?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <?=$catname?></h2>
	<p class="single-center-content">
		<?=$catdesc?>
	</p>
	<p class="single-center-content">
		Subcategories:<br />
		<br />
<?
	while($catr = mysql_fetch_array($catq))
	{
?>
		<a href="wiki.php?sub=<?=$catr['subcatID']?>"><?=$catr['subcatname']?></a> - <?=$catr['subcatinfo']?><br /><br />
<?
	}
?>
	</p>
</div>