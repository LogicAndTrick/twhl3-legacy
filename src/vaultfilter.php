<div class="single-center">
	<h1>Advanced Mapvault Filter</h1>
	<p class="single-center-content">
		Use this form to filter your results to exactly what you want. Note that this is not a search form, to search use the quick search near the top of the page or use the advanced search, available on the left sidebar.
	</p>
	<p class="single-center-content">
		To use this form, select the games you want to include in your search. Hold down ctrl to select more than one. Do the same with the categories. To search all, simply leave 'All' highlighted. You can also choose what must be included in the maps. If you dont tick all of them, the unticked options will be considered optional. Leave all three unticked if you don't want to filter by included content. Minimum rating is exactly what it says it is, and down the bottom you can choose how you want to order the results.
	</p>
</div>	
<div class="single-center" id="gap-fix">
	<h1>Filter</h1>
	<div class="filter">
		<form action="vaultdofilter.php" method="post">
			<div class="filter-left">
				Choose games:<br />
				<select multiple="multiple" name="game[]" size="8">
					<option selected="selected" value="0">All</option>
<?
					$res2 = mysql_query("SELECT * FROM mapgames ORDER BY gameorder ASC");
					while($rowe = mysql_fetch_array($res2)) {
?>
					<option value="<?=$rowe['gameID']?>"><?=$rowe['gamename']?></option>
<?
					}
?>
				</select>
			</div>
			<div class="filter-right">
				Must include:<br />
				(leave blank to ignore)<br />
				<input type="checkbox" name="RMF"/> RMF<br />
				<input type="checkbox" name="BSP"/> BSP<br />
				<input type="checkbox" name="MAP"/> MAP<br />
				<br />
				Minimum rating:
				<select name="minrating">
					<option value="0">0</option>
					<option value="0.5">0.5</option>
					<option value="1">1</option>
					<option value="1.5">1.5</option>
					<option value="2">2</option>
					<option value="2.5">2.5</option>
					<option value="3">3</option>
					<option value="3.5">3.5</option>
					<option value="4">4</option>
					<option value="4.5">4.5</option>
					<option value="5">5</option>
				</select>
			</div>
			<div class="filter-center">
				Choose categories:<br />
				<select multiple="multiple" name="cat[]" size="5">
					<option selected="selected" value="0">All</option>
<?
					$res = mysql_query("SELECT * FROM mapcats ORDER BY catorder ASC");
					while($rowg = mysql_fetch_array($res)) {
?>
					<option value="<?=$rowg['catID']?>"><?=$rowg['catname']?></option>
<?
					}
?>
				</select>
			</div>
			<div class="filter-bottom">
				Sort by:
				<select name="sort">
					<option value="postdate">Date (Default)</option>
					<option value="avgrating">rating</option>
					<option value="ratings">number of ratings</option>
					<option value="views">number of views</option>
					<option value="downloads">number of downloads</option>
				</select>
				<div class="filter-sort">
				    <input type="radio" checked="checked" value="DESC" name="order"/> Highest first (Default)<br />
				    <input type="radio" value="ASC" name="order"/> Lowest First
				</div>
				<input type="submit" value="Filter"/><br />
			</div>
		</form>
	</div>
</div>




