<?	
	$editid=mysql_real_escape_string($_GET['edit']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$editid'");
	$row = mysql_fetch_array($result);
	$authorid = $row['authorid'];
	$waiting = $row['waiting'];
	
	if (!((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))) fail("You aren't logged in, or you don't have permission to do this.","index.php");

			//$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$editid' AND isactive = '1' ORDER BY page ASC");
			if ($waiting == 1)
				$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$editid' ORDER BY page ASC");
			else
				$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$editid' AND isactive > 0 ORDER BY page ASC");
?>
<div class="single-center">
	<h1>Edit Tutorial</h1>
	<span class="page-index">
		Edit Page:
<?
			$counter = 0;
			while($row1 = mysql_fetch_array($result1))
			{
?>
				<a href="tutorial.php?edit=<?=$editid?>&amp;page=<?=$row1['page']?>"><?=$row1['page']?></a>
<?
				$counter++;
			}
			if ($counter < 5)
			{
?>
				<a href="tutcreatepage.php?id=<?=$gettut?>">[Add Page]</a>
<?
			}
?>
		<?/*<a href="tutcreatepage.php?id=<?=$editid?>">[Add Page]</a>*/?>
	</span>
	<h2><a href="tutorial.php">Tutorials</a> &gt; <a href="tutorial.php?id=<?=$editid?>"><?=$row['name']?></a> &gt; Edit</h2>
	<form method="post" action="tutchangetut.php?id=<?=$editid?>" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="tutname" value="<?=$row['name']?>" />Tutorial Name:<br />
				<input class="right" size="40" type="text" name="tuttopics" value="<?=$row['topics']?>" />Keywords:<br />
				<select class="right" name="tutdiff">
					<option value="0"<?=($row['difficulty'] == 0)?' selected="selected"':''?>>Beginner</option>
					<option value="1"<?=($row['difficulty'] == 1)?' selected="selected"':''?>>Intermediate</option>
					<option value="2"<?=($row['difficulty'] == 2)?' selected="selected"':''?>>Advanced</option>
				</select>Difficulty:<br />
				<select class="right" name="tutsect">
					<option value="1"<?=($row['catID'] == 1)?' selected="selected"':''?>>Goldsource</option>
					<option value="2"<?=($row['catID'] == 2)?' selected="selected"':''?>>Source</option>
					<option value="4"<?=($row['catID'] == 4)?' selected="selected"':''?>>General</option>
				</select>Section:
			</p>
			<p class="single-center-content">
				Description:<br />
				<textarea rows="3" cols="76" name="tutdesc"><?=$row['description']?></textarea>
			</p>
			<p class="single-center-content">
				<input class="right" size="70" type="file" name="tutexample" />Example Map:<br />
				<input class="right" size="70" type="text" name="tutcont" value="<?=$row['examplecont']?>" />Example Map Contents:<br />
				<input class="right" size="70" type="text" name="tutnotes" value="<?=$row['examplenotes']?>" />Example Map Notes:<br />
			</p>
			<p class="single-center-content">
				<input class="right" id="post" type="submit" value="Edit"/>
			</p>
		</fieldset>
	</form>
</div>