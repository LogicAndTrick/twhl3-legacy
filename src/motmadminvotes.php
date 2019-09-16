<?
	include 'middle.php';
	
	$month = mysql_real_escape_string($_GET['m']);
	$year = mysql_real_escape_string($_GET['y']);
	
	if (!isset($month) || !isset($year) || ($year=='') || ($month==''))
	{
		echo 'Error.';
		exit;
	}
	
	$totvoter=mysql_fetch_array(mysql_query("SELECT MAX(cnt) AS maxvotes, SUM(cnt) AS sumvotes FROM (SELECT votemap, name, count(*) AS cnt FROM motmvotes LEFT JOIN maps ON votemap = mapID WHERE voteyear = $year AND votemonth = $month GROUP BY votemap) as vq"));
	$totalvotes=$totvoter['sumvotes'];
	$maxvotes=$totvoter['maxvotes'];
	
	$voteq = mysql_query("SELECT votemap, name, count(*) AS cnt FROM motmvotes LEFT JOIN maps ON votemap = mapID WHERE voteyear = $year AND votemonth = $month GROUP BY votemap ORDER BY cnt DESC");
?>
<div class="vote-results">
<?
		while ($voter = mysql_fetch_array($voteq))
		{
?>
			<p class="result-item"><?=$voter['name']?></p>
			<div style="width: <?=ceil(($voter['cnt']/$maxvotes)*656)?>px;" class="result-graph"><?=$voter['cnt']?></div>
<?
		}
?>
</div>