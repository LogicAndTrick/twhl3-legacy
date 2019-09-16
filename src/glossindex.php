<?
	$glossq = mysql_query("SELECT * FROM glossarycats LEFT JOIN (SELECT count(*) AS cnt,glosscat FROM glossary GROUP BY glosscat) as gq ON glosscatID = glosscat");
?>

<div class="single-center">
	<h1>The TWHL Glossary</h1>
	<p class="single-center-content">
		Welcome to the TWHL Glossary! Here, you'll find an extensive guide to the range of terms you're likely to bump into while mapping. We've split it up into the different engines, and then into the seperate games. Common terms will appear for the games they are common for.
	</p>	
	<p class="single-center-content">
		Found a mistake in one of the guides? Want to add some content? <a href="#">Contact us</a>.
	</p>	
</div>
<div class="single-center" id="gap-fix">
	<h1>The Glossary</h1>
	<ul>
<?
	while ($glr = mysql_fetch_array($glossq))
	{
		if ($glr['cnt'])
		{
?>
		<li><a href="glossary.php?cat=<?=$glr['glosscatID']?>"><?=$glr['glosscatname']?></a> (<?=$glr['cnt']?>)</li>
<?
		}
	}
?>
	</ul>
</div>