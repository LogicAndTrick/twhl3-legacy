<?
die();
?><div class="single-center">
	<h1>TUTORIALCONVERTER3000</h1>
	<p class="single-center-content">
	Edit tutorials:<br /><br />
<?

$result = mysql_query("SELECT * FROM tutorials LEFT JOIN progress ON tutorialID = tut WHERE authorid > 0");
while ($row = mysql_fetch_array($result))
{
	?>
		<?=($row['done']==1)?'<span style="text-decoration: line-through">':''?><a href="tutorialconverter3000.php?tut=<?=$row['tutorialID']?>"><?=$row['name']?></a><?=($row['done']==1)?'</span>':''?><br />
	<?
}

?>
	</p>
</div>