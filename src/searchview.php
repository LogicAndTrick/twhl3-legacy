<?
	if ($valid)
	{		
		$url="search.php?searchstring=".urlencode(stripslashes($_GET['searchstring']))."&amp;searchlocation=".urlencode(stripslashes($_GET['searchlocation']))."&amp;page=";
		$resultcount = 30;
		$genind = generateindex("page",$resultcount,$searchq,5,$url);
		$startat = $genind[1];
		$indexlist = $genind[0];
		
		$sres = mysql_query($searchq . " LIMIT $startat,$resultcount");
		if (mysql_num_rows($sres) > 0)
		{
?>
<div class="single-center">
	<h1>Search Results</h1>
	<span class="page-index">
		<?=$indexlist?>
	</span>
	<h2>Search</h2>
	<table class="tutorial-index">
		<tr>
			<th><?=$row1?></th>
			<th><?=$row2?></th>
		</tr>
<?
			while ($ser = mysql_fetch_array($sres))
			{
?>
		<tr>
			<td><a href="<?=$row1link?><?=$ser['search1id']?>"><?=$ser['search1']?></a></td>
			<td><?=linesplitter($ser['search2'],35)?></td>
		</tr>
<?
			}
?>
	</table>
</div>
<?
		}
		else fail("No results were found containing that search string.","index.php");
	}
	else fail("Search location or search string not specified.","index.php");
?>