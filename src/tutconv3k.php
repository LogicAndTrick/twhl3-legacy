<?
die();
?>
<div class="single-center">
	<h1>TUTORIALCONVERTER3000 CONVERTER</h1>
	<p class="single-center-content">
	
	<?

function dateconv($wrongdate) {
	$str=$wrongdate;
		$yr=substr($str,0,4);
		$mt=substr($str,5,2);
		$dy=substr($str,8,2);
		$hr=0;
		$mn=0;
		$sc=0;
		//echo $yr . $mt . $dy . $hr . $mn . $sc . '<br><br>';
	return date("U",mktime($hr,$mn,$sc,$mt,$dy,$yr));
}

if ($_GET['convert']!="DOITNOW")
{
?>
Warning: Doing this will erase any edits you have previously made. Recommend you only do it once.<br /><br />
<a href="tutorialconverter3000.php?convert=DOITNOW">Click here to automatically create all the tutorials</a>
<?
}
elseif ($_GET['convert']=="DOITNOW")
{
?>
<a href="tutorialconverter3000.php">Back</a><br /><br />
<?

mysql_query("TRUNCATE TABLE tutorials");
mysql_query("TRUNCATE TABLE tutorialpages");

$counter = 1;
mysql_query('SET OPTION SQL_BIG_SELECTS=1', $dbh);
$result = mysql_query("SELECT * FROM twhl_tutorials order by TutorialID");
while($row = mysql_fetch_array($result))
	{
	/*
	TutorialID, Category, SubType, Name, Description, Topics, ContentFile, ExampleLink, Author, AddDate, ModDate, Hits, HitsStart
		 
	tutorialID, catID, difficulty, authorid, pages, name, description, topics, example, date, editdate, hits, hitdate, waiting

	pageID, tutorialid, page, subtitle, content
	 
	sectionID 	name 		level
	1 			Half-Life 	0
	2 			Source 		0
	3 			Drafts 		2
	*/
		$tid=$row['TutorialID'];
		while($tid!=$counter)
		{
			$sql="INSERT INTO tutorials (catID, difficulty, authorid, pages, name, description, topics, example, date, editdate, hits, hitdate, waiting) VALUES ('-1', '-1', '-1', '-1', 'deleted', 'deleted', 'deleted', 'deleted', '-1', '-1', '-1', '-1', '-1')";
			if (!mysql_query($sql,$dbh))
			{
				die('Error: ' . mysql_error());
			}
			echo 'Inserted deleted tutorial<br />';
			$counter+=1;
		}
		$catID=$row['Category'];
		$difficulty=$row['SubType']-1;
		$authorid=$row['Author'];
		$pages=1;
		$name=htmlfilter($row['Name']);
		$description=htmlfilter($row['Description']);
		$topics=htmlfilter($row['Topics']);
		$example=htmlfilter($row['ExampleLink']);
		$date=dateconv($row['AddDate']);
		$editdate=dateconv($row['ModDate']);
		$hits=$row['Hits'];
		$hitdate=$row['HitsStart'];
		$waiting=0;
		if ($catID == 10) 
		{
			$catID = 3;
			$waiting = 1;
		}
		$sql="INSERT INTO tutorials (catID, difficulty, authorid, pages, name, description, topics, example, date, editdate, hits, hitdate, waiting) VALUES ('$catID', '$difficulty', '$authorid', '$pages', '$name', '$description', '$topics', '$example', '$date', '$editdate', '$hits', '$hitdate', '$waiting')";
		if (!mysql_query($sql,$dbh))
		{
			die('Error: ' . mysql_error());
		}
		echo 'Successfully converted "' . $name . '"<br />';
		$content="";
		$filename = "tutorials/".$row["ContentFile"].".txt";
		$fp = @fopen ($filename, "r");
		if (!empty($fp)) {
			$content = fread ($fp, filesize ($filename));
		}
		fclose ($fp);
		$content = htmlfilter($content);
		$sql="INSERT INTO tutorialpages (tutorialid, page, subtitle, content) VALUES ('$tid', '1', '', '$content')";
		if (!mysql_query($sql,$dbh))
		{
			die('Error: ' . mysql_error());
		}
		echo 'Added 1 page to "' . $name . '"<br />';
		$counter+=1;
	}

}

?>
	</p>
</div>